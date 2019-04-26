
$(document).ready(function () {
    $.contextMenu({
        selector: '.pageNavButton', 
        callback: function(key, options) {
            var m = "clicked: " + key + " id " + options.$trigger[0].id;
            if (currentEditPageId == 9007199254740991)
            {
                if (key == "edit")
                {
                    loadEdit(options.$trigger[0]);
                }
                else if (key == "open")
                {
                    loadPage(options.$trigger[0]);
                }
                else if (key == "properties")
                {
                    $("#editPageName").val($("#" + options.$trigger[0].id).attr("data-name"));

                    //editPageTags        
                    var tags = JSON.parse($("#" + options.$trigger[0].id).attr("data-pagetags")); console.log(tags);
                    var tagArray = [];

                    $.each(tags, function (i, obj){
                        tagArray.push({tag: obj});
                    });

                    $('#editPageTags').chips({
                        placeholder: 'Voeg tag toe...',
                        secondaryPlaceholder: '+Tag',
                        data: tagArray,
                        autocompleteOptions: {
                            data: tagSuggestions,
                            limit: Infinity,
                            minLength: 1
                        }
                    });

                    M.updateTextFields();

                    $("#editPageModal").modal();
                    $("#editPageModal").modal("open");
                }
                else if (key == "rename")
                {
                    switchToInput(options.$trigger[0].children[0], options.$trigger[0].id);
                }
                else if (key == "delete") 
                {
                    if (confirm("Weet je zeker dat je deze pagina wilt verwijderen?"))
                    {
                        $.ajax({
                            type: 'POST',
                            url: 'deleteContextMenu.php',
                            data: {
                                'id' : options.$trigger[0].id
                            },
                            success: function(msg)
                            {
                                var response = $.parseJSON(msg);
                                
                                if (response.status == "ok")
                                {
                                    M.toast({ html: "Pagina succesvol verwijderd" });
                                    options.$trigger[0].remove();
                                }
                                else
                                {
                                    console.log('Added fail');
                                }
                            }
                        });
                    }
                }
                else
                    alert(m);

                window.console && console.log(m) ;
            }
        },
        items: {
            "open": {name: "Openen", icon: '' },
            "edit": {name: "Bewerken", icon: '' },
            "sep1": "---------",
            "delete": {name: "Verwijderen", icon: '' },
            "rename": {name: "Naam wijzigen", icon: '' },
            "sep2": "---------",
            "properties": {name: "Eigenschappen", icon: '' }
        }
    });
});
    