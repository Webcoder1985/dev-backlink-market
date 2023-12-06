<div class="row">
    {{-- <div class="col-md-4">
         <div class="form-group form-float">
             <label for="sellerSites">Site</label>
             <select type="hidden" id="sellerSites" name="seller_site_id" required class="form-control show-tick ms select2" tabindex="1">
                 @foreach($sites as $site)
                 <option value="{{$site->id}}" @if(!empty($page) && $page->seller_site_id == $site->id) selected @endif>{{$site->site_url}}</option>
                 @endforeach
             </select>
         </div>
     </div>--}}
    <input type="hidden" id="seller_site_id" value="{{$page->seller_site_id}}" name="seller_site_id" required
           class="form-control show-tick ms select2" tabindex="1">
    {{-- <div class="col-md-4">
         <div class="form-group form-float">
             <label for="sellerPageId">Page Id</label>
             <input type="hidden" min="1" class="form-control" placeholder="Page Id" name="seller_site_page_id" required="" aria-required="true" id="sellerPageId" aria-invalid="true" tabindex="2" value="@if(!empty($page) && $page->seller_site_page_id){{$page->seller_site_page_id}}@endif">
         </div>
     </div>--}}
    <input type="hidden" id="sellerSites"
           value="@if(!empty($page) && $page->seller_site_page_id){{$page->seller_site_page_id}}@endif"
           name="seller_site_page_id" required class="form-control show-tick ms select2" tabindex="1">
    <div class="col-md-10">
        <div class="form-group form-float">
            <label for="sellerPageUrl">Page URL</label>
            <input disabled type="text" class="form-control" name="seller_site_page_url"
                   {{--  required="" placeholder="Page URL"--}}  aria-required="true" id="sellerPageUrl"
                   aria-invalid="true" tabindex="3"
                   value="@if(!empty($page) && $page->seller_site_page_url){{$page->seller_site_page_url}}@endif">
        </div>
    </div>
    <div class="col-md-10">
        <div class="form-group form-float">
            <label for="title">Title</label>
            <input type="text" disabled class="form-control" placeholder="Title" name="title" required=""
                   aria-required="true" id="title" aria-invalid="true" tabindex="14"
                   value="@if(!empty($page) && $page->title){{$page->title}}@endif">
        </div>
    </div>
    <div class="col-md-7">
        <div class="form-group form-float">
            <label for="category">Category</label>
            <select id="categories" name="categories[]" class="categories" multiple="multiple" data-placeholder="Select Category">
                @foreach($categories as $category)
                    <optgroup label="{{ $category->title }}">
                        @foreach($category->children as $child_category)
                            @if(count($child_category->children)>0)
                                @foreach($child_category->children as $child_category1)
                                    @if(count($child_category1->children)>0)
                                        @foreach($child_category1->children as $child_category2)

                                            <option value="{{ $child_category2->level }}" @if(in_array($child_category2->level,$category_ar)) selected="selected" @endif>{{ $child_category2->title }}</option>
                                        @endforeach
                                    @else
                                        <option value="{{ $child_category1->level }}" @if(in_array($child_category1->level,$category_ar)) selected="selected" @endif>{{ $child_category1->title }}</option>
                                    @endif
                                @endforeach
                            @else
                                <option value="{{ $child_category->level }}" @if(in_array($child_category->level,$category_ar)) selected="selected" @endif>{{ $child_category->title }}</option>
                            @endif
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group form-float">
            <label for="price_user">Price</label>
            ({{Config::get('app.currency_symbol')}}
            )<input type="number" class="form-control" placeholder="Price" name="page_price_seller" required=""
                    aria-required="true" id="price_user" aria-invalid="true" tabindex="14"
                    value="@if(!empty($page) && $page->page_price_seller){{ (int)round($page->page_price_seller,0) }}@endif">
        </div>
    </div>
    {{--
    <div class="col-md-4">
        <div class="form-group form-float">
            <div class="checkbox">
                <input id="indexed" type="checkbox" value="1" name="indexed" @if(!empty($page) && $page->indexed) checked @endif>
                <label for="indexed">
                    Indexed
                </label>
            </div>
        </div>
    </div>
    --}}
    <div class="col-md-4">
        <div class="form-group form-float">
            <div class="checkbox">
                <input id="is_active" type="checkbox" value="1" name="is_active" @if(!empty($page) && $page->is_active) checked @endif>
                <label for="is_active">
                    Is Active?
                </label>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="{{(!empty($page) && $page->id) ? $page->id : 0}}" name="id">
