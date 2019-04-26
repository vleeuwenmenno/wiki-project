$(document).ready(function() {

    $('.deleteuserbtn').on('click', function(sender){
        if(confirm("Are you sure you want to delete this user?"))
        { 
            $.ajax({
                type: 'POST',
                url: 'deleteMember.php',
                data: {
                    'id': $(this).closest('tr').data("id")
                },
                success: function(msg)
                {
                    var response = $.parseJSON(msg);
                    
                    if (response.status == "ok")
                    {
                        M.toast({ html: "Member succesvol verwijderd" });
                        $(this).closest('tr').remove();
                    }
                    else if (response.status == "cannot_delete_active_user")
                    {
                        M.toast({ html: "Je kan jezelf niet verwijderen" });
                    }
                    else if (response.status == "403")
                    {
                        M.toast({ html: "Je hebt geen rechten om accounts te verwijderen!" });
                    }
                    else
                    {
                        console.log('Removal failed');
                    }
                }
            });
        }
    });

    $(".adminswitch").click(function() {
        $.ajax({
            type: 'POST',
            url: 'updateMembers.php',
            data: {
                'id': $(this).closest('tr').data("id"),
                'userType': $(this).closest('tr').data("userType")
            },
            success: function(msg)
            {
                var response = $.parseJSON(msg);
                
                if (response.status == "ok")
                {
                    M.toast({ html: "Member succesvol updated" });
                }
                else if (response.status == "cannot_update_active_user")
                {
                    M.toast({ html: "Je kan jezelf niet verwijderen van admin" });
                    $(this).attr('checked','')
                }
                else if (response.status == "403")
                {
                    M.toast({ html: "Je hebt geen rechten om accounts te updaten!" });
                    $(this).removeAttr('checked')
                }
                else
                {
                    console.log('Update failed');
                    console.log(data.userType);
                }
            }
        });
      });

    $('.dropdown-trigger').dropdown({
        inDuration: 300,
        outDuration: 225,
        constrainWidth: false,
        hover: false,
        coverTrigger: false,
        alignment: 'left'
      }
    );

    $('.tabs').tabs();
  });

  $(function(){
    $("#checkall").click(function () {
          $('.usercheck').prop('checked', this.checked);
    });
});
