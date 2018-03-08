jQuery(document).ready(function() {
    window.ecwidBlogPosts = [];
    debugger;
    
    if ( ecwidDashboardBlog.posts.length > 0 ) {
        renderBlogPosts(ecwidDashboardBlog.posts);
    } else {
        jQuery.getJSON( ecwidDashboardBlog.url, {}, function(data) {
            posts = [], mediaIds = [];
            
            for (var i = 0; i < data.length; i++) {
                post = data[i];
                window.ecwidBlogPosts[i] = {
                    'title': post.title.rendered,
                    'excerpt': post.excerpt.rendered,
                    'link': post.link,
                    'id': post.id
                };
                
            }
            
            renderBlogPosts();
            
            var deferreds = [];
            for (var i = 0; i < data.length; i++) {
                deferreds[deferreds.length] = jQuery.getJSON( ecwidDashboardBlog.mediaUrl + data[i].featured_media, function(data) {
                   jQuery( '.ecwid-post-id-' + data.post + ' img' ).attr('src', data.media_details.sizes.thumbnail.source_url);
                   
                   for (var i = 0; i < window.ecwidBlogPosts.length; i++) if (window.ecwidBlogPosts[i].id == data.post ) {
                       window.ecwidBlogPosts[i].imageUrl = data.media_details.sizes.thumbnail.source_url;
                   }
                });
            }
            
            jQuery.when.apply(jQuery, deferreds).done(function() {
               sendPosts();
            });
            
            sendPosts = function() {
                jQuery.ajax({
                    'url': ajaxurl + '?action=' + ecwidDashboardBlog.saveAction,
                    'method': 'POST',
                    'data': {
                        'posts': window.ecwidBlogPosts
                    }
                });
            };
            
            sendPosts.posts = posts;
        } );
    }
    
    function renderBlogPosts(posts) {
        if (!posts) {
            posts = window.ecwidBlogPosts;
        }
        for (var i = 0; i < posts.length; i++) {
            var post = jQuery('#ecwid_blog_feed .template-container .blog-post').clone();
            post.addClass('ecwid-post-id-' + posts[i].id);
            jQuery('.post-title', post).attr('href', posts[i].link);
            jQuery('.post-title', post).html(posts[i].title);
            jQuery('.post-excerpt', post).html(posts[i].excerpt);
            
            if (posts[i].imageUrl) {
                jQuery('img', post).attr('src', posts[i].imageUrl);
            }
            
            post.appendTo('#ecwid_blog_feed ul.posts');
        }
    }
});