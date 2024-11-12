@props(['groupName', 'options', 'label'])
<script>

function updateSelectedOption(element, optionType, parent, grandparent=0, subsuboptionRef=0) {
    
    /* REDIS count update
    var redisKey = 'invoice_views:' + element.getAttribute('data-ref');
    fetch('/increment-redis-counter?key=' + redisKey, {
        method: 'GET'
    }).catch(error => console.error('Error incrementing Redis counter:', error));
    */

    var currentSelection = document.getElementById(optionType + '_' + selectedOptionRefs[optionType]);
    console.log(currentSelection, element, optionType, parent, grandparent, subsuboptionRef);
    if (currentSelection) {
        currentSelection.checked = false;
    }
    var price = parseFloat(element.getAttribute('data-price'));
    var ref = element.getAttribute('data-ref');
    var newSelection = document.getElementById(optionType + '_' + ref);

    if (newSelection) {
        newSelection.checked = true;
        if (grandparent>0) {
            var grandparentSelection = document.getElementById(optionType + '_' + grandparent);
            grandparentSelection.checked = true;
        }
    }

    if (subsuboptionRef>0) {
        var subsuboptionSelection = document.getElementById(optionType + '_' + subsuboptionRef);
        subsuboptionSelection.checked = true;
    }

    // Update the global variables
    selectedOptions[optionType] = element.getAttribute('data-title-label');
    selectedOptionRefs[optionType] = element.getAttribute('data-ref');
    selectedOptionPrices[optionType] = element.getAttribute('data-price');

    // Update the UI
    document.getElementById('title_label_' + optionType).textContent = element.getAttribute('data-title-label');
    toggleOptions(parent, '', optionType);
    calculateTotalPrice();
}

function calculateTotalPrice() {
    let total = 0;
    
    for (let optionType in selectedOptionPrices) {
        total += parseFloat(selectedOptionPrices[optionType] || 0);
    }
    
    const formattedTotal = new Intl.NumberFormat('ru-RU', {
        style: 'currency',
        currency: 'RUB',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(total);
    
    document.getElementById('totalPrice').textContent = formattedTotal;
    document.getElementById('mobileTotalPrice').textContent = formattedTotal;
}

function toggleOptions(ref, defaultSelection = '', groupName) {
    var optionGroup = document.getElementById(groupName + '_option_group');
    var suboptions = document.getElementById('suboptions_' + ref);
    // Hide all suboptions in this option group
    var allSuboptions = optionGroup.querySelectorAll('.suboptions');
    allSuboptions.forEach(function(suboption) {
        suboption.style.display = 'none';
    });

    // Show the selected option's suboptions if they exist
    if (suboptions) {
        suboptions.style.display = 'block';
    }

    if (defaultSelection) {
        var defaultOption = document.getElementById('label_' + defaultSelection);
        var defaultOptionRef = document.getElementById(groupName + '_' + defaultSelection);
        if (defaultOption) {
            defaultOption.click();
            defaultOptionRef.checked = true;
        }
    }
}

function selectWoodType(element, groupName, optionRef, suboptionRef, subsuboptionRef) {
    // Select the wood type
    element.previousElementSibling.checked = true;

    // Find the first subsuboption and select it
    var firstSubsuboption = element.closest('.wood-type-row').querySelector('.sizes input[data-default="true"]');
    if (firstSubsuboption) {
        var firstSubsuboptionLabel = firstSubsuboption.nextElementSibling;
        updateSelectedOption(firstSubsuboptionLabel, groupName, optionRef, suboptionRef, subsuboptionRef);
    }
}

</script>
<style>
    .simple-options {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 3fr));
        gap: 10px;
    }
    
    .wood-type-options {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .non-hover {
        pointer-events: none;
    }

    .mobile-break {
    display: none;
    width: 100%;
    }

    @media (max-width: 768px) {
        .mobile-break {
            display: block;
        }
    }
    
    @media (max-width: 768px) {
        .option-group .btn-group {
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        .option-group .btn-group .btn {
            width: 100%;
            margin-bottom: 5px;
        }
        .wood-type-row {
            flex-direction: column;
        }
        .wood-type, .sizes {
            width: 100%;
        }
        .sizes {
            margin-top: 5px;
        }
        .simple-options {
            grid-template-columns: 1fr;
        }
    }
</style>
<div class="option-group" id="{{ $groupName }}_option_group">
    <label class="btn btn-outline-light title-btn w-100 mb-2 non-hover" id="title_label_{{ $groupName }}" disabled>{{ $label }}</label>
    <!-- Level 2: Main options -->
    <div class="btn-group main-options w-100" role="group" aria-label="{{ $groupName }} button group">
        @foreach($options as $option)
            <input type="radio" class="btn-check" name="{{ $groupName }}" id="{{ $groupName }}_{{ $option->ref }}" autocomplete="off">
            <label class="btn btn-outline-light {{ $groupName }}-label w-100" 
                for="{{ $groupName }}_{{ $option->ref }}" 
                @if ($option->suboptions->isNotEmpty())
                    data-ref="{{ $option->ref }}"
                    onclick="toggleOptions('{{ $option->ref }}', '{{ $option->unique_default ?? '' }}', '{{ $groupName }}')"
                @else
                    onclick="updateSelectedOption(this, '{{ $groupName }}', '{{ $option->ref }}')"
                    data-ref="{{ $option->ref }}"
                    data-title-label="{{ $option->site_tab_label . ' - ' . $option->site_label }}"
                    data-price="{{ $option->data_price ?? 0 }}"
                @endif> {{ $option->site_label }}</label>
        @endforeach
    </div>
    <div class="suboptions-container">
        @foreach($options as $option)
            @if($option->suboptions->isNotEmpty())
                <div class="suboptions w-100" id="suboptions_{{ $option->ref }}" style="display: none;">
                    @if($option->suboptions->first()->suboptions->isEmpty())
                        <div class="simple-options">
                    @else
                        <div class="wood-type-options">
                    @endif
                        @foreach($option->suboptions as $suboption)
                            @if($suboption->suboptions->isEmpty())
                                <div>
                                    <input type="radio" class="btn-check" name="sub{{ $groupName }}" id="{{ $groupName }}_{{ $suboption->ref }}" autocomplete="off">
                                    <label id="label_{{ $suboption->ref }}" class="btn btn-outline-light w-100" 
                                        data-ref="{{ $suboption->ref }}" 
                                        data-title-label="{{ $option->site_tab_label . ' - ' . $option->site_label . ' - ' . $suboption->site_sub_label}}" 
                                        data-price="{{ $suboption->data_price }}" 
                                        for="{{ $groupName }}_{{ $suboption->ref }}" 
                                        onclick="updateSelectedOption(this, '{{ $groupName }}', {{ $option->ref }})">
                                        {{ $suboption->site_sub_label }}
                                    </label>
                                </div>
                            @else
                                <div class="wood-type-row d-flex flex-wrap">
                                    <div class="wood-type flex-grow-1">
                                        <input type="radio" class="btn-check" name="sub{{ $groupName }}" id="{{ $groupName }}_{{ $suboption->ref }}" autocomplete="off">
                                        <label id="label_{{ $suboption->ref }}" class="btn btn-outline-light btn-wood wood-type-label w-100" 
                                            data-ref="{{ $suboption->ref }}" 
                                            onclick="toggleOptions('{{ $suboption->ref }}', '{{ $suboption->unique_default ?? '' }}', '{{ $groupName }}')">
                                            {{ $suboption->site_sub_label }}
                                        </label>
                                    </div>
                                    <div class="mobile-break"></div>
                                    <div class="sizes d-flex flex-wrap flex-grow-1">
                                        @foreach($suboption->suboptions as $index => $subsuboption)
                                            <input type="radio" class="btn-check" name="subsub{{ $groupName }}" id="{{ $groupName }}_{{ $subsuboption->ref }}" autocomplete="off" {{ $index === 0 ? 'data-default="true"' : '' }}>
                                            <label id="label_{{ $subsuboption->ref }}" class="btn btn-outline-light flex-grow-1 m-1" 
                                                data-ref="{{ $subsuboption->ref }}" 
                                                data-title-label="{{ $option->site_tab_label . ' - ' . $option->site_label . ' - ' . $suboption->site_sub_label . ' - ' . $subsuboption->site_level4_label}}" 
                                                data-price="{{ $subsuboption->data_price }}" 
                                                for="{{ $groupName }}_{{ $subsuboption->id }}" 
                                                onclick="updateSelectedOption(this, '{{ $groupName }}', {{ $option->ref }}, {{ $suboption->ref }})">
                                                {{ $subsuboption->site_level4_label }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>