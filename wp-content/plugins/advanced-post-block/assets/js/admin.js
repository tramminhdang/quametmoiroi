function handleShortcode(id) {
	var input = document.querySelector('#apbFrontShortcode-' + id + ' input');
	var tooltip = document.querySelector('#apbFrontShortcode-' + id + ' .tooltip');
	input.select();
	input.setSelectionRange(0, 30);
	document.execCommand('copy');
	tooltip.innerHTML = wp.i18n.__('Copied Successfully!', 'advanced-post-block');
	setTimeout(() => {
		tooltip.innerHTML = wp.i18n.__('Copy To Clipboard', 'advanced-post-block');
	}, 1500);
}