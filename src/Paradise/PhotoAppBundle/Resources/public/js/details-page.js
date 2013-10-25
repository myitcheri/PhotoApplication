    $("#add_comment").autoGrow();
    
    //ADD COMMENT
    $('#addComment').submit(function() {
        var comment = $("#add_comment").val();
        
        var msg = comment.replace(/\r\n|\r|\n/g,"<br />");
        
        var id=$("#photo_id").val();
        
        if( comment.length !== 0 ) {
            $.ajax({
                type: "GET",
                url: '/comment/create/',
                data: 'id='+id+'&comment='+msg+'&uid='+$('#user_id').val(),
                error: function() {},
                success: function(response){
                    $('#addComment')[0].reset();
                    
                    var res = $.parseJSON(response); 
                    
                    var commentMsg ='<div class="commentMsg" id="fm_'+res.commentID+'">\n\
                                     <a href="javascript:void(0)" onclick="deleteComment();return false;"><div class="deteleComment fb-delete pre-delete" id="'+res.commentID+'"></div></a>\n\
                                     <img class="img" src="/bundles/paradisephotoapp/images/avatar-default.jpg" width="50" height="50">\n\
                                       <ul class="comment_info"> \n\
                                         <li class="profileName">John Doe</li>\n\
                                         <li class="datepost">'+res.datepost+'</li>\n\
                                       </ul>\n\
                                     <span>'+res.msg+'</span>\n\
                                     <div class="clear"></div>\n\
                                  </div>';
                    $("#newComment").prepend(commentMsg).fadeIn('slow');
                    
                    if(res.totalComments === 0){
                        $(".total_comments").html("0 comment");
                    }else if(res.totalComments === 1){
                        $(".total_comments").html(res.totalComments+" comment");
                    }else{
                        $(".total_comments").html(res.totalComments+" comments");
                    }//comment counter
                }
            });
        }
        return false;
    });
    
    //DELETE COMMENT
    $(".deteleComment").hide();
    $("div[id^=fm_]").hover(
        function () {
          $(this).find('.deteleComment').show();
        },
        function () {
          $(this).find('.deteleComment').hide();
        }
    );
   
    function deleteComment(){
        $(".fb-delete").click( function(){
            var id=$(this).attr("id");
            var photoID=$('#photo_id').val();
            if(confirm("Are you sure you want to delete this post?")){
                $.ajax({
                    type: "GET",
                    url: '/comment/delete/',
                    data: 'id='+id+'&photoID='+photoID,
                    error: function() { 
                            callback(); 
                    },
                    success: function(response){
                        var res = $.parseJSON(response);

                        if(res.totalComments === 0){
                            $(".total_comments").html("0 comment");
                        }else if(res.totalComments === 1){
                            $(".total_comments").html(res.totalComments+" comment");
                        }else{
                            $(".total_comments").html(res.totalComments+" comments");
                        } //comment counter
                    }
                });
                $(this).parents("#fm_"+id).animate({ backgroundColor: "#fbc7c7" }, "fast").animate({ opacity: "hide" }, "slow");

            }
            return false;
        });
    }
       
    //LIKE PHOTO   
    function likePhoto(){
        $.ajax({
            url: '/like',
            type: 'GET',
            data: { 
                photoID : $('#photo_id').val(), 
                userID : $('#user_id').val()
            },
            error: function() { },
            success: function(item) {
                 if(item === 1)
                 {
                     $(".like_counter").html("You like this photo"); 
                 }
                 else
                 {
                     var total = parseInt(item) -1;   
                     $(".like_counter").html("You and "+total+" other(s) like this photo");  
                 }    
                 
                 $("#likeContainer").html('\
                                        <div class="pluginButton fb-like-active">\n\
                                            <a href="javascript: void(0)" onclick="deleteLike()"><span class="fb-like-icons like_active"></span></a>\n\
                                            <span class="context"> Like </span>\n\
                                        </div>');
            }
        });
        return false;
    } 
    
    function deleteLike(){
        $.ajax({
            url: '/deletelike',
            type: 'GET',
            data: { 
                photoID : $('#photo_id').val(), 
                userID : $('#user_id').val()
            },
            error: function() { },
            success: function(item) {
                 
                 $(".like_counter").html(item +" other(s) like this photo");  
                  
        
                 $("#likeContainer").html('\
                                         <div class="pluginButton likePhoto" id="likePhoto">\n\
                                            <a href="javascript: void(0)" onclick="likePhoto()"><span class="fb-like-icons like_thumb"></span></a>\n\
                                            <span class="context"> Like </span>\n\
                                         </div>');
            }
        });
        return false;
    }
    
    
    //TAGS PANEL
    $('#photoTags').hide();
    $('#selectTags').hide();

    var options = [];  

    options =  $.parseJSON($('#photoTags').val());

    var $select = $('#photo-tags').selectize({
            maxItems: null,
            plugins: ['remove_button'],
            valueField: 'unique',
            labelField: 'title',
            searchField: 'unique',
            optgroups: [
                    {value: 'persons', label: 'Persons'},
                    {value: 'workgroup', label: 'Work Group'},
                    {value: 'events', label: 'Events'},
                    {value: 'keywords', label: 'Keywords'}
            ],
            optgroupField: 'category',
            options: options, 
            create: function(input) {
                 return { title: input, unique: '4,'+input+',0', category: 'keywords,enableDelete'};
            },
            render: {
                option: function(item, escape) {
                    var unique = item.unique;
                    title = unique.split(',');
                    return '<div class="option">'+ escape(title[1]) +'</div>';
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/tags',
                    type: 'GET',
                    dataType: 'json',
                    data: { 
                        searchword : query 
                    },
                    error: function() { 
                        callback(); 
                    },
                    success: function(res) {
                        callback(res.tags); 
                    }
                });
            },   
            onItemAdd: function(item){
                $.ajax({
                    url: '/tags/create/',
                    type: 'GET',
                    data: { 
                        q : item, 
                        id: $('#photo_id').val(),
                        uid: $('#user_id').val()
                    },
                    error: function() { 
                       console.log('Error occurred while saving to database');
                    },
                    success: function(res){
                       //console.log(res);
                    }
                });
            },
            onDelete: function(item){
                $.ajax({
                    url: '/tags/delete/',
                    type: 'GET',
                    data: { 
                        id: $('#photo_id').val(),
                        q : item
                    },
                    error: function() { 
                       console.log('Error occurred while delete from database');
                    },
                    success: function(){
                       //console.log(res);
                    }
                });
            }   
        });

    var control = $select[0].selectize;

    selectTags =  $.parseJSON($('#selectTags').val());

    control.setValue(selectTags);
    
