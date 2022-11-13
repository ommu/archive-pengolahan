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
            if (itemData.manuver == false) {
                this.setLabel(item, {
                    label: itemData.code + ': ' + itemData.label +'<br/><a class="modal-btn" href="'+itemData['view-url']+'" title="Info '+itemData.code+': '+itemData.label+'">Info</a> | <a href="'+itemData['update-url']+'" title="Update '+itemData.code+': '+itemData.label+'">Update</a> | <a href="'+itemData['child-url']+'" title="Childs '+itemData.code+': '+itemData.label+'">Childs</a>',
                });
            } else {
                this.setLabel(item, {
                    label: itemData.code + ': ' + itemData.label +'<br/><a href="'+itemData['menuver-url']+'" title="Manuver '+itemData.code+': '+itemData.label+'">Manuver</a>',
                });
            }
		}
	});
});