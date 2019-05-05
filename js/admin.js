jQuery(document).ready(function(){
    jQuery(".post-type").each(function(){
       var _element = jQuery(this);
        if(_element.prop('checked') === true){
            var _post_taxonomy = _element.val();
            jQuery.ajax({
                 url : ajax_url,
                 type : 'post',
                 data : {
                     _post_taxonomy : _post_taxonomy,
                     action: "postsbytaxonomy"
                 },
                 success : function( response ) {
                     _element.next(".posts-list").html(response);
                     _element.next(".posts-list").show();
                     bindEvents();
                 }
                 
            });
        } else {
            _element.next(".posts-list").html("");
            _element.next(".posts-list").hide();
        }
    });
    jQuery(".post-type").click(function(){
       var _element = jQuery(this);
        if(_element.prop('checked') === true){
            var _post_taxonomy = _element.val();
            jQuery.ajax({
                 url : ajax_url,
                 type : 'post',
                 data : {
                     _post_taxonomy : _post_taxonomy,
                     action: "postsbytaxonomy"
                 },
                 success : function( response ) {
                     _element.next(".posts-list").html(response);
                     _element.next(".posts-list").show();
                     bindEvents();
                 }
            });
        } else {
            _element.next(".posts-list").html("");
            _element.next(".posts-list").hide();
        }
    });
});

function bindEvents() {
    jQuery(".posts-list .selectall").click(function(){
        var _element = jQuery(this);
        _element.parent().find("." + _element.data("taxonomy")).attr('checked', true);
        return false;
    });
    jQuery(".posts-list .unselectall").click(function(){
        var _element = jQuery(this);
        _element.parent().find("." + _element.data("taxonomy")).attr('checked', false);
        return false;
    });
}