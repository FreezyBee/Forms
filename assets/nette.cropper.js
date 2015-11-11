(function ($, undefined) {

    $('.netteCropperFileUpload').change(function () {

        var cropValid = false;
        var cropInfo = {};
        var fileUpload = this;
        var files = this.files;
        var settings = $(this).data('nette-cropper');

        $("<div id='netteCropper' class='modal fade'></div>").appendTo('body');
        $('#netteCropper').html("<div id='netteCropperDialog' class='modal-dialog'></div>");
        $('#netteCropperDialog').html("<div id='netteCropperContent' class='modal-content'></div>");
        $('#netteCropperContent').html("<div id='netteCropperHeader' class='modal-header'></div><div id='netteCropperBody' class='modal-body'></div><div id='netteCropperFooter' class='modal-footer'></div>");
        $('#netteCropperHeader').html("<a class='close netteCropperCancel' data-dismiss='modal' aria-hidden='true'>×</a><h4 class='modal-title' id='netteCropperTitle'></h4>");
        $('#netteCropperBody').html('<div style="width: 100%; height: 300px"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs="></div>');
        $('#netteCropperFooter').html(
            '<a class="btn btn-sm btn-success-outline netteCropperZoom pull-left" data-zoom="0.1">+</a>' +
            '<a class="btn btn-sm btn-success-outline netteCropperZoom pull-left" data-zoom="-0.1">-</a>' +
            '<a id="netteCropperOk" class="btn btn-sm btn-primary-outline" data-dismiss="modal">Uložit</a>' +
            '<a class="btn btn-sm btn-danger-outline netteCropperCancel" data-dismiss="modal">Zrušit</a>');

        $('#netteCropperOk').click(function () {

            // validace
            if (cropInfo.width < settings.minWidth) {
                window.alert('Min. dovolená šířka obrázku je ' + settings.minWidth + 'px');
                return false;
            } else if (cropInfo.height < settings.minWidth) {
                window.alert('Min. dovolená výška obrázku je ' + settings.minHeight + 'px');
                return false;
            } else {
                $('.netteCropperOldPreview').hide();
            }
        });

        $('.netteCropperCancel').click(function () {
            fileUpload.value = '';
        });

        $('#netteCropper').on('hidden.bs.modal', function () {
            $('#netteCropper').remove();
        });

        $('#netteCropper').on('shown.bs.modal', function () {
            var cropper = new Cropper($('#netteCropperBody > div > img')[0], {
                aspectRatio: settings.aspectRation,
                autoCropArea: 0.65,
                dragCrop: false,
                cropBoxMovable: true,
                cropBoxResizable: false,
                crop: function (e) {
                    cropInfo = e;
                    var json = [
                        '{"x":' + e.x,
                        '"y":' + e.y,
                        '"height":' + e.height,
                        '"width":' + e.width,
                        '"rotate":' + e.rotate + '}'
                    ].join();
                    $('.netteCropperJson').val(json);
                }
            });

            if (cropper && files && files.length) {
                file = files[0];

                if (/^image\/\w+/.test(file.type)) {
                    blobURL = window.URL.createObjectURL(file);
                    cropper.reset().replace(blobURL);
                } else {
                    window.alert('Please choose an image file.');
                }
            }

            $('.netteCropperZoom').click(function () {
                cropper.zoom($(this).data('zoom'));
            });
        });

        $('#netteCropper').modal('show');
    });

})(jQuery);