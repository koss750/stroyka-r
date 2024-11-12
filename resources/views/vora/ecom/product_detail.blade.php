@extends('layouts.default')
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();
   for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
   k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(97430601, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/97430601" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Foundation -->
                            <div class="size-filter">
                                <p class="text-content">Фундамент</p>
                                <h4 class="m-b-15">Тип фундамента</h4>
                                <div class="btn-group-horizontal" data-bs-toggle="buttons">
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="foundation" id="fNone" value="fNone"> Без Фундамента
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="foundation" id="fVinta" value="fVinta" data-child-selector=".fVinta-children"> Винтовая
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="foundation" id="fLenta" value="fLenta"> Ленточный
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="foundation" id="fBeton" value="fBeton" data-child-selector=".fBeton-children"> ЖБ
                                    </label>
                                </div>
                                
                                <!-- Child Options -->
                                <div class="fVinta-children mt-2" style="display: none;">
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="foundation-type" id="Vinta8925" value="Vinta8925"> 89х2500
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="foundation-type" id="Vinta8930" value="Vinta8930"> 89х3000
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="foundation-type" id="Vinta10825" value="Vinta10825"> 108х2500
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="foundation-type" id="Vinta10830" value="Vinta10830"> 108х3000
                                    </label>
                                </div>
                                
                                <div class="fBeton-children mt-2" style="display: none;">
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="foundation-type" id="Beton153" value="Beton153"> 150х3000
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="foundation-type" id="Beton154" value="Beton154"> 150х4000
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="foundation-type" id="Beton203" value="Beton203"> 200х3000
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="foundation-type" id="Beton204" value="Beton204"> 200х4000
                                    </label>
                                </div>
                            </div>

                            <!-- HomeKit -->
                            <div class="size-filter mt-4">
                                <p class="text-content">Домокомплект</p>
                                <h4 class="m-b-15">Тип комплекта</h4>
                                <div class="btn-group-horizontal" data-bs-toggle="buttons">
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="housekit" id="OCB" value="OCB" data-child-selector=".OCB-children"> ОЦБ
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="housekit" id="Glue" value="Glue" data-child-selector=".Glue-children"> Клееный
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="housekit" id="Rr" value="Rr" data-child-selector=".Rr-children"> Ручной Рубки
                                    </label>
                                </div>
                                
                                <!-- Child Options -->
                                <div class="OCB-children mt-2" style="display: none;">
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="housekit-wood" id="OCBSos" value="OCBSos" data-grandchild-selector=".OCBSos-grandchildren"> Сосна
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="housekit-wood" id="OCBList" value="OCBList" data-grandchild-selector=".OCBList-grandchildren"> Лиственница
                                    </label>
                                </div>

                                <div class="OCBSos-grandchildren mt-2" style="display: none;">
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="housekit-size" id="OCBSos200" value="OCBSos200"> 200
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="housekit-size" id="OCBSos220" value="OCBSos220"> 220
                                    </label>
                                </div>

                                <div class="OCBList-grandchildren mt-2" style="display: none;">
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="housekit-size" id="OCBList200" value="OCBList200"> 200
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="housekit-size" id="OCBList220" value="OCBList220"> 220
                                    </label>
                                </div>

                                <div class="Glue-children mt-2" style="display: none;">
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="housekit-size" id="Glue145160" value="Glue145160"> 145х160
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="housekit-size" id="Glue145200" value="Glue145200"> 145х200
                                    </label>
                                </div>

                                <div class="Rr-children mt-2" style="display: none;">
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="housekit-wood" id="RrSos" value="RrSos"> Сосна
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="housekit-wood" id="RrList" value="RrList"> Лиственница
                                    </label>
                                </div>
                            </div>

                            <!-- Roofing -->
                            <div class="size-filter mt-4">
                                <p class="text-content">Кровля</p>
                                <h4 class="m-b-15">Тип кровли</h4>
                                <div class="btn-group-horizontal" data-bs-toggle="buttons">
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="roofing" id="rRub" value="rRub"> Рубероид
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="roofing" id="rMetal" value="rMetal"> Металочерепица
                                    </label>
                                    <label class="btn btn-outline-primary light btn-sm me-2">
                                        <input type="radio" class="position-absolute invisible" name="roofing" id="rSoft" value="rSoft"> Мягкая
                                    </label>
                                </div>
                            </div>

                            <!-- Form for capturing customization -->
                            <form id="customizationForm">
                                <input type="hidden" name="customizationSelection" id="customizationSelection">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div id="visit-count" style="display:none;">
                Visits: {{ $count }}
            </div>
        </div>
    </div>
</div>

<!-- CSS -->
<style>
.btn.active {
    background-color: #007bff;
    color: #fff;
}

.btn-outline-primary.light:hover {
    background-color: #007bff;
    color: #fff;
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const radioButtons = document.querySelectorAll('input[type="radio"]');

    // Function to handle changes
    function handleRadioChange() {
        const isChildRadio = this.name === 'foundation-type' || this.name === 'housekit-wood';
        const isGrandchildRadio = this.name === 'housekit-size';

        if (!isChildRadio && !isGrandchildRadio) {
            const parentGroup = document.querySelectorAll(`input[name="${this.name}"]`);
            parentGroup.forEach(radio => {
                radio.parentElement.classList.toggle('active', radio.checked);
            });

            // Handle child element visibility
            if (this.dataset.childSelector) {
                document.querySelectorAll(this.dataset.childSelector).forEach(child => {
                    child.style.display = 'flex';
                    // Hide grandchildren for new selections
                    child.querySelectorAll('div').forEach(grandchild => {
                        grandchild.style.display = 'none';
                    });
                });
            }

            // Hide other children within the same parent group
            document.querySelectorAll(this.dataset.childSelector).forEach(child => {
                child.style.display = 'flex';
                child.querySelectorAll('div').forEach(grandchild => {
                    grandchild.style.display = 'none';
                });
            });
        } else if (isChildRadio && !isGrandchildRadio) {
            this.parentElement.classList.add('active');

            // Handle grandchild element visibility
            if (this.dataset.grandchildSelector) {
                document.querySelectorAll(this.dataset.grandchildSelector).forEach(grandchild => grandchild.style.display = 'flex');
            }

            // Hide other grandchildren within the same child group
            document.querySelectorAll(this.dataset.grandchildSelector).forEach(grandchild => {
                grandchild.style.display = 'none';
                grandchild.querySelectorAll('input[type="radio"]').forEach(r => {
                    r.checked = false;
                    r.parentElement.classList.remove('active');
                });
            });
        } else if (isGrandchildRadio) {
            this.parentElement.classList.add('active');
        }

        // Log the state
        const foundationSelection = document.querySelector('input[name="foundation"]:checked') ? document.querySelector('input[name="foundation"]:checked').value : "";
        const housekitSelection = document.querySelector('input[name="housekit"]:checked') ? document.querySelector('input[name="housekit"]:checked').value : "";
        const roofingSelection = document.querySelector('input[name="roofing"]:checked') ? document.querySelector('input[name="roofing"]:checked').value : "";
        console.log(`Foundation: ${foundationSelection}, HomeKit: ${housekitSelection}, Roofing: ${roofingSelection}`);
    }

    // Attach event listeners to radio buttons
    radioButtons.forEach(button => {
        button.addEventListener('change', handleRadioChange);
    });
});
</script>
@endsection
