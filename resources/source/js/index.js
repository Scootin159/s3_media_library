//=include common/common.inc.js

$(document).ready(function () {

    $('#main_gallery_template').each(function() {
        var view = Backbone.View.extend({
            template: _.template($(this).html())            
        });

        $.get({
            url: 'api/thumbnails.php?cmd=get_thumbnails',
            success: function(data) {
                console.log(data);
                $("#main_gallery").html(new view().template(data));
            }
        });
    });
});