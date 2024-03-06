// см. addons\swiftfin\models\documents\mt\widgets\assets\TagAsset::getMaskDefinition
$.extend($.inputmask.defaults.definitions, $definition);
$(document).ready(function () {
	$('input[data-mask]').each(function (idx) {
		var mask = $(this).data('mask');
		$(this).inputmask(mask);
	});
});
