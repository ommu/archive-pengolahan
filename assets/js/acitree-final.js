$.noConflict();

jQuery(document).ready(function ($) {
	// listen for the events before we init the tree
	$('#tree').on('acitree', function(event, api, item, eventName, options) {
		var itemId = api.getId(item);
		// do some stuff on init
        if (eventName == 'added') {
            if (itemId == selectedId) {
				// then select it
				api.select(item);
			}
		}
	});
	// init the tree
	$('#tree').aciTree({
		ajax: {
			url: treeDataUrl,
		},
		selectable: true,
		itemHook: function(parent, item, itemData, level) {
            this.setLabel(item, {
                label: itemData.level + ': ' + itemData.code + '<br/>' + itemData.label,
            });
		}
	});
});