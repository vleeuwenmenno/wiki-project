function addNewLanguageBtn_Click()
{
    alert("Not implemented");
}

function visibleCards()
{
    var c =0;
    $('.group-card-thing').each(function(i, obj) {
        if ($(obj).is(':visible'))
            c++;
    });
    return c;
}

function addGroupBtn_Click()
{
    //Let's gather all tags by retrieving the chips instance
    var elem = document.getElementById('languageTags');
    var chipsInstance = M.Chips.getInstance(elem);

    //Grab data from inputs
    var languageName = $("#languageName").val();
    var languageIconPre = $("#languageIcon").val();
    var languageDeveloper = $("#languageDeveloper").val();
    var languageTags = [];
    var languageIcon = languageIconPre;

    try
    {
        //Let's construct the correct icon class
        languageIcon = "devicon" + "-" + languageIconPre + "-" + iconsJson.find(x => x.name === languageIconPre).versions.font[0];
    }
    catch (ex){}

    //Let's add all tags from the chip instance
    $.each(chipsInstance.chipsData, function(key, value) {
        languageTags.push(value.tag);
    });

    //Post the data to addGroup to actually add the group
    $.ajax({
        type: 'POST',
        url: 'addGroup.php',
        data: { 
            'languageName': languageName,
            'languageIcon': languageIcon,
            'languageDeveloper': languageDeveloper,
            'languageTags': languageTags
        },
        success: function(msg)
        {
            var response = $.parseJSON(msg);
            
            if (response.status == "ok")
            {
                console.log('Added success');
                window.location = "index.php";
            }
            else
            {
                console.log('Added fail');
            }
        }
    });
}

function destroyGroupBtn_Click()
{
    console.log("Remove group with id " + $("#editGroupModal").attr("data-languageid")   + "?");

    //Grab data from inputs
    var languageName = $("#editLanguageName").val();
    var langId = $("#editGroupModal").attr("data-languageid");

    //Ask the user to confirm deletion of group and all its pages
    if (confirm("Weet je zeker dat de groep `" + languageName + "` wilt verwijderen?\n\nLET OP! Dit zal ook alle pagina's onder deze groep verwijderen."))
    {
        //Post the data to destroy to actually group
        $.ajax({
            type: 'POST',
            url: 'destroyGroup.php',
            data: { 
                'langId': langId
            },
            success: function(msg)
            {
                var response = $.parseJSON(msg);
                
                if (response.status == "ok")
                {
                    console.log('Annihilated with success');
                    window.location = "index.php";
                }
                else
                {
                    console.log('Annihilation failed, retreating...');
                }
            }
        });
    }
}

function updateGroupBtn_Click()
{
    //Let's gather all tags by retrieving the chips instance
    var elem = document.getElementById('editLanguageTags');
    var chipsInstance = M.Chips.getInstance(elem);
    
    //Grab data from inputs
    var languageId = $("#editGroupModal").attr("data-languageId");
    var languageName = $("#editLanguageName").val();
    var languageIconPre = $("#editLanguageIcon").val();
    var languageDeveloper = $("#editLanguageDeveloper").val();
    var languageTags = [];

    //Let's construct the correct icon class
    var languageIcon = "";
    try
    {
        languageIcon = "devicon" + "-" + languageIconPre + "-" + iconsJson.find(x => x.name === languageIconPre).versions.font[0];
    }
    catch(err) 
    {
        languageIcon = languageIconPre;
    }

    //Let's add all tags from the chip instance
    $.each(chipsInstance.chipsData, function(key, value) {
        languageTags.push(value.tag);
    });

    //Post the data to addGroup to actually add the group
    $.ajax({
        type: 'POST',
        url: 'updateGroup.php',
        data: {
            'languageId': languageId,
            'languageName': languageName,
            'languageIcon': languageIcon,
            'languageDeveloper': languageDeveloper,
            'languageTags': languageTags
        },
        success: function(msg)
        {
            var response = $.parseJSON(msg);
            
            if (response.status == "ok")
            {
                console.log('Added success');
                window.location = "index.php";
            }
            else
            {
                console.log('Added fail');
            }
        }
    });
}


$(document).ready(function () {
    $('.modal').modal();

    $("#cancelFilterBtn").click(function () {
        $("#cancelFilterBtn").fadeOut("fast");
    });

    $(".editGroupBtn").click(function () {
        //editLanguageName
        $("#editLanguageName").val($(this).attr("data-languageName"));

        //editLanguageIcon
        $("#editLanguageIcon").val($(this).attr("data-languageIcon"));

        $("#editDeviconPreviewer").removeAttr('class');
        $("#editDeviconPreviewer").attr('class', '');
        $("#editDeviconPreviewer").addClass("devicon-preview");
        $("#editDeviconPreviewer").addClass($(this).attr("data-languageIcon"));

        //editLanguageDeveloper
        $("#editLanguageDeveloper").val($(this).attr("data-languageDeveloper"));

        //editLanguageTags        
        var tags = JSON.parse($(this).attr("data-languageTags")); console.log(tags);
        var tagArray = [];

        $.each(tags, function (i, obj){
            tagArray.push({tag: obj});
        });

        $('#editLanguageTags').chips({
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

        var gId = $(this).attr("data-languageId");
        $("#editGroupModal").attr("data-languageId", gId);
        $("#editGroupModal").modal("open");
    });
});

// js for tags filter animation on cards
$(function(){
    $("#tagAnimation").mixItUp({
        animation: {
            enable: true,
            duration: 150
        }
    });

    $('#tagAnimation').on('mixEnd', function() {
        console.log(visibleCards() + "< Visible " + cardCount + "< All")
        if (visibleCards() < cardCount)
        {
            $("#cancelFilterBtn").fadeIn("fast");
        }
    });
});