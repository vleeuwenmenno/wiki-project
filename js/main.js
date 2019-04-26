var simplemde = null;
var currentEditPageId = 9007199254740991;

var switchToInput = function (sender, id) 
{
    var label = $(sender);
 
    //Add a TextBox next to the Label.
    label.after("<input type='text' style='display:none; margin: 0 12 0 12; width: 280px;' id='txt" + id + "' />");

    //Reference the TextBox.
    var textbox = $(sender).next();

    //Set the name attribute of the TextBox.
    textbox[0].name = sender.id.replace("lbl", "txt");

    //Assign the value of Label to TextBox.
    textbox.val(label.html());

    //When Label is clicked, hide Label and show TextBox.
    $(sender).hide();
    $(sender).next().show();
    textbox.focus();

    var someMethod = function (e) {
        if(e.which == 13 || e.type == "focusout")
            if (textbox.val())
            {
                $(this).hide();
                $(this).prev().html($(this).val());
                $(this).prev().show();

                $.ajax({
                    type: 'POST',
                    url: 'updatePage.php',
                    data: {
                        'wikiPageId': id,
                        'pageTitle': textbox.val()
                    },
                    success: function(msg)
                    {
                        M.toast({ html: "Pagina titel successvol gewijzigt" });
                    }
                });

                $("#" + id).attr("data-name", textbox.val());
                
                textbox.remove();
                console.log("Updated text for " + id);
            }
            else
            {
                $(this).hide();
                $(this).prev().html($(this).val());
                $(this).prev().show();

                $("#" + id).find("a").html($("#" + id).data("name"));

                textbox.remove();
                console.log("Cancelled text update for " + id);
            }
    };

    //When focus is lost from TextBox, hide TextBox and show Label.
    textbox.focusout(someMethod);
    textbox.keypress(someMethod);
};

function getUrlParameter(sParam) 
{
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
}

function loadPage(sender)
{
    var pageId = sender.id;

    $(".pageNavButton").removeClass("active");
    $(sender).addClass("active");
    
    $("#pageContents").html('<div class="center-loader preloader-wrapper big active">\
                                <div class="spinner-layer spinner-blue-only">\
                                <div class="circle-clipper left">\
                                    <div class="circle"></div>\
                                </div><div class="gap-patch">\
                                    <div class="circle"></div>\
                                </div><div class="circle-clipper right">\
                                    <div class="circle"></div>\
                                </div>\
                                </div>\
                            </div>');
    
    $.ajax({
        type: 'GET',
        url: 'loadPage.php',
        data: {
            'lang': getUrlParameter("lang"),
            'id': pageId
        },
        success: function(msg)
        {
            $("#pageContents").hide();

            $("#pageContents").html(msg);

            $('pre > code').each(function() {
                hljs.highlightBlock($(this)[0]);
            });

            $("#pageContents").fadeIn("fast");

            $("#pageTitleLabel").html($("#" + pageId).attr("data-name"));
        }
    });
}

function loadEdit(sender)
{
    currentEditPageId = sender.id;
    $(".pageNavButton").removeClass("active");
    $(sender).addClass("active");

    $('#savePageBtn').show();
    $('#closePageEditBtn').show();

    $('#backBtn').addClass("disabled");
    $('#backBtn').attr("href", "javascript:void(0)");
    
    //Hide page content

    $("#pageContents").fadeOut("fast", function() {
        $.ajax({
            type: 'GET',
            url: 'loadPage.php',
            data: {
                'lang': getUrlParameter("lang"),
                'id': currentEditPageId,
                'markdown': ''
            },
            success: function(msg)
            {
                //Initialize editor
                simplemde = new SimpleMDE({
                    element: $("#mde")[0],
                    initialValue: msg,
                    renderingConfig: {
                        codeSyntaxHighlighting: true,
                    },
                    indentWithTabs: false,
                    tabSize: 4,
                    promptURLs: false,
                    autofocus: true,
                });

                $("#pageTitleLabel").html("[Bewerkmodus] " + $("#" + currentEditPageId).attr("data-name"));
            }
        });
    });
}

function attachmentBtn_Click()
{
    
}

$(document).ready(function () {
    $("#closePageEditBtn").click(function(sender) {
        $("#pageTitleLabel").html($("#" + currentEditPageId).attr("data-name"));
        $.ajax({
            type: 'GET',
            url: 'loadPage.php',
            data: {
                'lang': getUrlParameter("lang"),
                'id': currentEditPageId,
                'markdown': ''
            },
            success: function(msg)
            {
                if (simplemde.value() == msg)
                {
                    simplemde.toTextArea();
                    $("#mde").hide();
                    simplemde = null;

                    $("#pageContents").fadeIn();

                    $('#savePageBtn').hide();
                    $('#closePageEditBtn').hide();

                    $('#backBtn').removeClass("disabled");
                    $('#backBtn').attr("href", "index.php");
            
                    $.ajax({
                        type: 'GET',
                        url: 'loadPage.php',
                        data: {
                            'lang': getUrlParameter("lang"),
                            'id': currentEditPageId
                        },
                        success: function(msg)
                        {
                            $("#pageContents").html(msg);
                            $('pre > code').each(function() {
                                hljs.highlightBlock($(this)[0]);
                            });            
                        }
                    });
                    
                    currentEditPageId = 9007199254740991;
                }
                else
                {
                    if (confirm('Waarschuwing! Document is aangepast maar niet opgeslagen, weet je zeker dat je het document wilt sluiten?'))
                    {
                        simplemde.toTextArea();
                        $("#mde").hide();
                        simplemde = null;

                        $("#pageContents").fadeIn();

                        $('#savePageBtn').hide();
                        $('#closePageEditBtn').hide();

                        $('#backBtn').removeClass("disabled");
                        $('#backBtn').attr("href", "index.php");
                
                        $.ajax({
                            type: 'GET',
                            url: 'loadPage.php',
                            data: {
                                'lang': getUrlParameter("lang"),
                                'id': currentEditPageId
                            },
                            success: function(msg)
                            {
                                $("#pageContents").html(msg);
                                $('pre > code').each(function() {
                                    hljs.highlightBlock($(this)[0]);
                                });                
                            }
                        });
                        
                        currentEditPageId = 9007199254740991;
                    }
                    else
                        $("#pageTitleLabel").html("[Bewerkmodus] " + $("#" + currentEditPageId).attr("data-name"));
                }
            }
        });
    });

    $("#savePageBtn").click(function(sender) {
        $.ajax({
            type: 'GET',
            url: 'loadPage.php',
            data: {
                'lang': getUrlParameter("lang"),
                'id': currentEditPageId,
                'markdown': ''
            },
            success: function(msg)
            {
                if (simplemde.value() == msg)
                { M.toast({ html: "Document is al opgeslagen" }); }
                else
                {
                    $.ajax({
                        type: 'POST',
                        url: 'savePage.php',
                        data: {
                            'id': currentEditPageId,
                            'page': simplemde.value()
                        },
                        success: function(msg)
                        {
                            var response = $.parseJSON(msg);
                            
                            if (response.status == "ok")
                            {
                                M.toast({ html: "Document succesvol opgeslagen" });
                            }
                            else
                            {
                                console.log('Added fail');
                            }
                        }
                    });
                }
            }
        });
    });

    $(".pageNavButton").click(function(sender) {
        if (currentEditPageId == 9007199254740991)
        {
            loadPage(sender.currentTarget);
        }
    });

    $('.sidenav').sidenav();
    $('.dropdown-trigger').dropdown({
        inDuration: 300,
        outDuration: 225,
        constrainWidth: false,
        hover: false,
        coverTrigger: false,
        alignment: 'left'

    }
    );

    $('.modal').modal();

    if (getUrlParameter("id") != null)
        $.ajax({
            type: 'GET',
            url: 'loadPage.php',
            data: {
                'lang': getUrlParameter("lang"),
                'id': getUrlParameter("id")
            },
            success: function(msg)
            {
                console.log("Autoload wikiPageId: "+ getUrlParameter("id"));
                $("#pageContents").html(msg);
                $('pre > code').each(function() {
                    hljs.highlightBlock($(this)[0]);
                });
    
                $("#pageTitleLabel").html($("#" + getUrlParameter("id")).attr("data-name"));
            }
        });
});

function addPageBtn_Click()
{
    //Let's gather all tags by retrieving the chips instance
    var elem = document.getElementById('addPageTags');
    var chipsInstance = M.Chips.getInstance(elem);
    
    //Grab data from inputs
    var languageId = getUrlParameter("lang");
    var pageTitle = $("#pageName").val();
    var pageTags = [];

    //Let's add all tags from the chip instance
    $.each(chipsInstance.chipsData, function(key, value) {
        pageTags.push(value.tag);
    });

    //Post the data to addGroup to actually add the group
    $.ajax({
        type: 'POST',
        url: 'addPage.php',
        data: {
            'languageId': languageId,
            'pageTitle': pageTitle,
            'pageTags': pageTags
        },
        success: function(msg)
        {
            var response = $.parseJSON(msg);
            
            if (response.status == "ok")
            {
                console.log('Added success');

                $("#pageName").val("");
                populateAddPageTags();

                $("#addPageModal").modal("close");
                $("#page-buttons").html($("#page-buttons").html() + '<li id="' +response.wikiPageId+ '" data-pagetags=\'' + JSON.stringify(pageTags) + '\' data-name="' +pageTitle+ '" class="pageNavButton"><a class="" href="javascript:void(0)">' + pageTitle + '</a></li>');
            
                $(".pageNavButton").click(function(sender) {
                    if (currentEditPageId == 9007199254740991)
                    {
                        loadPage(sender.currentTarget);
                    }
                });
            }
            else
            {
                console.log('Added fail');
            }
        }
    });
}

function openSidenavBtn_Click() {
    var instance = M.Sidenav.getInstance(".searchBar");
    instance.open();
}

function createNewPageBtn_Click() {
    alert("Not implemented");
}

function changeToolbarOrderBtn_Click() 
{
    $("#page-buttons").sortable({ 
        animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
        handle: "i", // Restricts sort start click/touch to the specified element
        draggable: "i", // Specifies which items inside the element should be sortable
        onUpdate: function (evt) {
            var item = evt.item;
        },
        onEnd: function (evt) {
            var item = evt.item;
        },
        placeholder: '<li id="placeholderDragDrop"><a class="placeholder">Hierheen verplaatsen...</a></li>',
        onDragStart: function ($item, container, _super) {
            oldIndex = $item.index();
            $item.appendTo($item.parent());
            
            $($item).hide();
            $("#placeholderDragDrop").html('<a class="placeholder">Test</a>');

            _super($item, container);
          },
          onDrop: function  ($item, container, _super) {
            var field,
            
            newIndex = $item.index();
        
            if(newIndex != oldIndex) {
              $item.closest('li').find('li').each(function (i, row) {
                row = $(row);
                if(newIndex < oldIndex) {
                  row.children().eq(newIndex).before(row.children()[oldIndex]);
                } else if (newIndex > oldIndex) {
                  row.children().eq(newIndex).after(row.children()[oldIndex]);
                }
              });
            }
            $($item).show();
            _super($item, container);
          }
    }); // init
    
    $(".pageNavButton").each(function(i, obj) {
        if ($(this).hasClass("edit-mode"))
        {
            $(this).removeClass("edit-mode");
            $(this).find("i").remove();
            $("#changeToolbarOrderBtn").html("<i class=\"material-icons\">edit</i>Toolbar volgorde aanpassen");
        }
        else
        {
            $(this).find("a").html("<i class=\"fas fa-bars pageNavFilterButton\"></i>" + $(this).find("a").html());
            $(this).addClass("edit-mode");
            $("#changeToolbarOrderBtn").html("<i class=\"material-icons\">save</i>Toolbar volgorder opslaan");
        }
    });

    if ($("#changeToolbarOrderBtn").html() == "<i class=\"material-icons\">edit</i>Toolbar volgorde aanpassen")
    {
        var indexes = {};
        $(".pageNavButton").each(function (key, value) {
            indexes[value.id] = key;
        });

        console.log(indexes);

        $.ajax({
            type: 'POST',
            url: 'indexUpdatePage.php',
            data: {
                'lang': getUrlParameter("lang"),
                'indexes': indexes
            },
            success: function(msg)
            {
                var response = $.parseJSON(msg);
            
                if (response.status == "ok")
                {
                    M.toast({html: "Volgorde succesvol opgeslagen"});
                }
                else
                {
                    M.toast({html: "Fout bij het opslaan van de volgorder"});
                    console.log(msg);
                }
            }
        });
    }
}



$(document).ready(function(){
    $('.scrollspy').scrollSpy({
        getActiveElement: function(id)
        {
            // console.log(i);
            return 'a[href="#' + id + '"]';
        }
    });
  });
