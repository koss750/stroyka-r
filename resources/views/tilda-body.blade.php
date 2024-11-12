@include('components.top')
<div id="allrecords" data-tilda-export="yes" class="t-records" data-hook="blocks-collection-content-node" data-tilda-project-id="8683967" data-tilda-page-id="44160913" data-tilda-formskey="b7c2abff9663579673a008ea48683967" data-tilda-cookie="no" data-tilda-lazy="yes" data-tilda-root-zone="com">

    @include('partials.header')

    <div id="rec711160086" class="r t-rec t-rec_pt_135 t-rec_pb_165" style="padding-top:135px; padding-bottom:165px; background-image:linear-gradient(0.486turn,rgba(9,32,63,1) 0%,rgba(83,120,149,1) 100%);" data-record-type="851" data-bg-color="linear-gradient(0.486turn,rgba(9,32,63,1) 0%,rgba(83,120,149,1) 100%)">
        <div class="t851">
            @include('partials.section-title')

            <div class="container-fluid">
                <div class="row">
                    @foreach ($cards as $card)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="card">
                                <!-- Card Image -->
                                <div class="card-image">
                                    @if(isset($card['media']))
                                        @foreach($card['media'] as $image)
                                            <img src="{{ $image->getUrl('thumb') }}" alt="{{ $card['title'] }}">
                                        @endforeach
                                    @else
                                        <img src="path/to/placeholder/image.jpg" alt="Placeholder">
                                    @endif
                                    <!-- Title Overlay -->
                                    <div class="card-title-overlay">
                                        <a href="{{ $card['link'] }}" class="main-title-link"><h2 class="main-title">{{ $card['title'] }}</h2></a>
                                    </div>
                                </div>

                                <!-- Card Details Overlay -->
                                <div class="card-details-overlay">
                                    <span class="model-name">{{ $card['title'] }}</span>
                                    <span class="dimensions-area-container">
                                        <span class="dimensions">{{ $card['dimensions'] ?? 'N/A' }}</span> m<sup class="sup-text">2</sup>
                                    </span>
                                    <span class="price">{{ $card['description'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="t851__fundament">Фундаменты</div>

            <div class="container-fluid">
                <div class="row">
                    @foreach ($foundations as $foundation)
                        <div class="col-xl-2 col-lg-3 col-md-4">
                            <div class="card">
                                <!-- Card Image -->
                                <div class="card-image">
                                    @if(isset($foundation['image_url']))
                                        <img class="img-fluid card-img" src="{{ asset($foundation['image_url']) }}" alt="{{ $foundation['title'] }}">
                                    @else
                                        <img class="img-fluid card-img" src="path/to/placeholder/image.jpg" alt="Placeholder">
                                    @endif
                                    <!-- Title Overlay -->
                                    <div class="card-title-overlay">
                                        <a href="{{ $foundation['link'] }}" class="main-title-link"><h2 class="main-title">{{ $foundation['title'] }}</h2></a>
                                    </div>
                                </div>

                                <!-- Card Details Overlay -->
                                <div class="card-details-overlay">
                                    <span class="model-name">{{ $foundation['title'] }}</span>
                                    <span class="dimensions-area-container">
                                        <span class="dimensions">{{ $foundation['dimensions'] ?? 'N/A' }}</span> m<sup class="sup-text">2</sup>
                                    </span>
                                    <span class="price">{{ $foundation['description'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

</div>

<style>
    /* Consolidated styles here */
 /* Global Styles */
 body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    /* Header Styles */
    .t454 {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
        background-color: #ffffff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .t454__maincontainer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 5%;
    }

    .t454__logowrapper {
        display: none;
    }

    .t454__leftwrapper {
        display: flex;
        align-items: center;
    }

    .t454__rightwrapper {
        padding: 0;
        text-align: right;
    }

    .t454__list {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .t454__region-select {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #ffffff;
    color: #333333;
    font-size: 16px;
    font-weight: 600;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 8px center;
    background-size: 18px;
    padding-right: 28px;
}

    .t454__list_item {
        padding: 0 15px;
    }

    .t-menu__link-item {
        color: #ffffff;
        font-weight: 600;
        text-decoration: none;
    }

    .t454__stroyka {
        font-weight: 600;
        text-decoration: none;
        cursor: default;
        margin-right: 20px;
    }

    .t454__searchbar {
        display: flex;
        align-items: left;
    }

    .t454__searchinput {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-right: 10px;
        width: 200px;
    }

    .t454__searchbutton {
        padding: 8px 16px;
        background-color: #333333;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    /* Section Title Styles */
    .t-section__topwrapper {
        margin-bottom: 20px;
    }

    .t-section__title {
        font-size: 28px;
        font-weight: bold;
    }

    .t-section__descr {
        font-size: 16px;
        color: #ffffff;
    }

    /* Card Styles */
    .card-image {
        position: relative;
        height: 200px; /* Set a fixed height */
    }

    .card-image img {
        height: 100%; /* Ensure the image fills the set height */
        width: 100%; /* Ensure the image fills the width */
        object-fit: cover; /* Maintain aspect ratio */
    }

    .card {
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-title-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        padding: 10px;
        text-align: center;
    }

    .main-title {
        font-size: 24px;
        margin: 0;
    }

    .main-title-link {
        color: #fff;
        text-decoration: none;
    }

    .main-title-link:hover {
        text-decoration: none;
    }

    .card-details-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        background: rgba(0, 0, 0, 0.7);
        color: #fff;
        padding: 10px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .model-name {
        font-size: 20px;
        font-weight: bold;
    }

    .dimensions-area-container {
        font-size: 18px;
        margin-top: 5px;
    }

    .sup-text {
        font-size: 14px;
        vertical-align: super;
    }

    .price {
        font-size: 22px;
        font-weight: bold;
        margin-top: 10px;
        color: #ffdd57;
    }

    .t851__container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .t851__col {
        flex: 0 0 calc(20% - 20px);
        padding: 10px;
    }

    .t851__table {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        height: 120px;
    }

    .t851__bg {
        width: 100%;
        height: 80px;
        background-size: cover;
        background-position: center;
    }

    .t851__textwrapper {
        padding: 5px;
    }

    .t-card__title {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 2px;
    }

    .t-card__descr {
        font-size: 12px;
        color: #666666;
    }

    .t-card__btn {
        display: inline-block;
        padding: 4px 8px;
        background-color: #333333;
        color: #ffffff;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s ease;
        font-size: 12px;
    }

    .t-card__btn:hover {
        background-color: #555555;
    }

    .t851__fundament {
        text-align: center;
        margin-top: 20px;
        font-size: 18px;
        font-weight: bold;
        color: #ffffff;
    }

    /* Footer Styles */
    .t389__maincontainer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #333333;
        color: #ffffff;
    }

    .t389__col {
        flex: 1;
    }

    .t389__list {
        list-style: none;
        padding: 0;
        margin: 0;
        text-align: center;
    }

    .t389__list_item {
        display: inline-block;
        margin: 0 10px;
    }

    .t389__typo {
        color: #ffffff;
        text-decoration: none;
    }

    .t389__typo:hover {
        text-decoration: underline;
    }
</style>


