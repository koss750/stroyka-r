<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceType;



class InvoiceController extends Controller
{
    /**
     * Fetch all InvoiceType models with their full hierarchical structure.
     *
     * @return array
     */
    public function fullList()
    {
        // Fetch all top-level items where 'parent' is presumably null or not set.
        $topLevelItems = InvoiceType::where('depth', 0)->get();

        // Recursively populate children
        $allItems = $topLevelItems->map(function ($item) {
            return $this->populateChildren($item);
        });

        $result = $allItems->toArray();
        return $result;
    }

    public function getDependent($ref)
    {
        return InvoiceType::where('parent', $ref)->get()->toArray();
    }

    /**
     * Recursively populates children for a given InvoiceType.
     *
     * @param  \App\Models\InvoiceType $item
     * @return \App\Models\InvoiceType
     */
    private function topCategories()
    {
        $topCategories = InvoiceType::where('depth', 0)->get()->toArray();
    }

    public function triggerInvoiceView()
    {
        return view('trigger-invoice-view');
    }

    public function invoiceViewReferences(Request $request)
    {
        $data = $request->all();
        $invoices = unserialize(base64_decode($request->data));
        $designTitle = $invoices[0];
        $invoices = $invoices[1];
        $page_title = "Смета для вашей конфигурации по $designTitle";
        $page_description = 'Примерно такую смету вы можете ожидать приобритая их. Они доступны онлайн, в PDF и даже в Эксельках';
        return view('vora.ecom.str', compact('page_title', 'page_description', 'invoices'));
    }

    /**
     * Recursively populates children for a given InvoiceType.
     *
     * @param  \App\Models\InvoiceType $item
     * @return \App\Models\InvoiceType
     */
    private function populateChildren($item)
    {
        $children = $item->children()->get();
        if ($children->isNotEmpty()) {
            $item->params = $children->map(function ($child) {
                return $this->populateChildren($child);
            });
        } else {
            $item->params = [];
        }

        return $item;
    }

    /**
     * Process submitted fields and create a structured array.
     *
     * @param Collection $fields
     * @return array
     */
    public function processNovaFormFields($fields)
    {
        $relationships = [];
        $topCategories = InvoiceType::where('depth', 0)->get();
        foreach ($topCategories as $parent) {
            $choice = [];
            $youngestChildren = $this->findYoungestChildren($parent);
            $choice = array_intersect($youngestChildren, $fields);
            foreach ($choice as $key=>$attempt) {
                $relationships[$parent->label] = $choice[$key]->label;
            }
            
        }
        return $relationships;
    }

    public function getByLabel($label) {
        return InvoiceType::where('label', $label)->first();
    }

    public function getByRef($ref) {
        return InvoiceType::where('ref', $ref)->first();
    }

    /**
     * Recursively find the oldest parent of the field.
     *
     * @param object $field
     * @return string|null
     */
    private function findOldestParent($field)
    {
        // Assuming the parent field is stored in a 'parent' attribute
        if (isset($field->parent) && $field->parent>0) {
            
            return $this->findOldestParent($this->getByRef($field->parent));
        }
        return $field ?? null; // Adjust according to how your fields store labels or identifiers
    }

    /**
     * Recursively find the youngest child's value.
     *
     * @param object $field
     * @return string|null
     */
    private function findYoungestChildren($parent)
    {
        $children = $parent->children()->get();
        $youngestChildren = [];

        if ($children->isEmpty()) {
            // If there are no children, this is a youngest child (leaf node).
            return [$parent];  // Return parent itself as the youngest child if it has no children.
        }

        foreach ($children as $child) {
            // Recursively find youngest children for each child
            $descendantYoungestChildren = $this->findYoungestChildren($child);
            $youngestChildren = array_merge($youngestChildren, $descendantYoungestChildren);
        }

        return $youngestChildren;
    }

    private function isChildOf($parent, $candidate) {
        if (isset($candidate->parent) && $candidate->parent>0) {
            $candidate = $this->findOldestParent($this->getByRef($candidate->parent));
        }
        if ($parent->id == $candidate->id) return true ?? false;
    }

    /**
     * Check if the item has children.
     *
     * @param object $item
     * @return bool
     */
    private function hasChildren($item)
    {
        // Implement the check based on your data structure
        // For example, you might check if the 'children' attribute is populated
        return isset($item->children) && count($item->children) > 0;
    }
}
