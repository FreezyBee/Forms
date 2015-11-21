(function ($, undefined) {

    $('.netteCropperFileUpload').change(function () {

        var loaded = false;
        var fileUpload = this;
        var files = this.files;
        var settings = $(this).data('nette-cropper');

        $("<div id='netteCropper' class='modal fade'></div>").appendTo('body');
        $('#netteCropper').html("<div id='netteCropperDialog' class='modal-dialog'></div>");
        $('#netteCropperDialog').html("<div id='netteCropperContent' class='modal-content'></div>");
        $('#netteCropperContent').html("<div id='netteCropperHeader' class='modal-header'></div><div id='netteCropperBody' class='modal-body'></div><div id='netteCropperFooter' class='modal-footer'></div>");
        $('#netteCropperHeader').html("<a class='close netteCropperCancel' data-dismiss='modal' aria-hidden='true'>×</a><h4 class='modal-title' id='netteCropperTitle'></h4>");
        $('#netteCropperBody').html('<div style="width: 100%; height: 300px"><img id="netteCropperImage" src="data:image/gif;base64,R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs="></div>');
        $('#netteCropperFooter').html(
            '<a class="btn btn-sm btn-success-outline netteCropperZoom pull-left" data-zoom="0.1">+</a>' +
            '<a class="btn btn-sm btn-success-outline netteCropperZoom pull-left" data-zoom="-0.1">-</a>' +
            '<a id="netteCropperOk" class="btn btn-sm btn-primary-outline" data-dismiss="modal">Uložit</a>' +
            '<a class="btn btn-sm btn-danger-outline netteCropperCancel" data-dismiss="modal">Zrušit</a>');

        $('#netteCropperOk').click(function () {

            var cropInfo = $('#netteCropperImage').cropper('getData');

            // validace
            if (cropInfo.width < settings.minWidth) {
                window.alert('Min. dovolená šířka obrázku je ' + settings.minWidth + 'px');
                return false;
            } else if (cropInfo.height < settings.minHeight) {
                window.alert('Min. dovolená výška obrázku je ' + settings.minHeight + 'px');
                return false;
            } else {
                $('.netteCropperOldPreview').hide();
                $('.netteCropperJson').val([
                    '{"x":' + cropInfo.x,
                    '"y":' + cropInfo.y,
                    '"height":' + cropInfo.height,
                    '"width":' + cropInfo.width,
                    '"rotate":' + cropInfo.rotate + '}'
                ].join());
            }
        });

        $('.netteCropperCancel').click(function () {
            fileUpload.value = '';
        });

        $('#netteCropper').on('hidden.bs.modal', function () {
            $('#netteCropper').remove();
        });

        $('#netteCropper').on('shown.bs.modal', function () {
            var cropper = $('#netteCropperImage').cropper({
                viewMode: 1,
                aspectRatio: settings.aspectRatio,
                autoCropArea: 0.65,
                dragCrop: false,
                cropBoxMovable: true,
                cropBoxResizable: false,
                built: function () {
                    if (!loaded) {
                        loaded = true;
                        if (files && files.length) {
                            file = files[0];

                            if (/^image\/\w+/.test(file.type)) {
                                blobURL = window.URL.createObjectURL(file);
                                $('#netteCropperImage').cropper('replace', blobURL);
                            } else {
                                $('#netteCropper').modal('hide');
                                fileUpload.value = '';
                                window.alert('Vyberte prosím obrázek.');
                            }
                        }
                    }
                }
            });

            $('.netteCropperZoom').click(function () {
                $('#netteCropperImage').cropper('zoom', $(this).data('zoom'));
            });
        });

        $('#netteCropper').modal('show');
    });

    $('[data-form-confirm]').click(function (e) {

        if (window.netteFormSubmit !== undefined && window.netteFormSubmit === true) {
            return true;
        }

        e.preventDefault();
        var obj = this;

        $('<div id="netteFormConfirm" class="modal fade"></div>').appendTo('body');
        $('#netteFormConfirm').html('<div id="netteFormConfirmDialog" class="modal-dialog"></div>');
        $('#netteFormConfirmDialog').html('<div id="netteFormConfirmContent" class="modal-content"></div>');
        $('#netteFormConfirmContent').html('<div id="netteFormConfirmHeader" class="modal-header"></div><div id="netteFormConfirmBody" class="modal-body"></div><div id="netteFormConfirmFooter" class="modal-footer"></div>');
        $('#netteFormConfirmHeader').html('<a class="close" data-dismiss="modal" aria-hidden="true">×</a>');
        $('#netteFormConfirmBody').html('<p>' + $(obj).data('form-confirm') + '</p>');
        $('#netteFormConfirmFooter').html(
            '<a id="netteFormConfirmOk" class="btn btn-sm btn-danger-outline" data-dismiss="modal">Ano</a>' +
            '<a class="btn btn-sm btn-secondary-outline" data-dismiss="modal">Ne</a>');

        $('#netteFormConfirmOk').click(function () {
            window.netteFormSubmit = true;
            $(obj).click();
        });

        $('#netteFormConfirm').modal('show');

        return false;
    });

})(jQuery);