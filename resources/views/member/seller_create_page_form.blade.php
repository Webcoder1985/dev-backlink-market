<div class="row">
    <div class="col-md-12 mb-2">Do you miss a page from your Blog? Try to manually add it here.</div>
    <div class="col-md-12">
        <div class="form-group form-float">
            <label for="sellerPageId">Page URL(s)</label>
            <input type="hidden" name="seller_site_id" value="{{$site->id}}" />
            <textarea rows="8" name="seller_site_page_urls" required="" aria-required="true" aria-invalid="true" class="form-control"></textarea>
        </div>
    </div>
</div>
<input type="hidden" value="{{(!empty($page) && $page->id) ? $page->id : 0}}" name="id">
