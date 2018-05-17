jQuery(document).ready(function() {
    window.ecwidBlogPosts = [];
    
    if (ecwidDashboardBlog.posts.length > 0) {
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
                    var imageUrl = ecwidDashboardBlog.imagesCDN + data.media_details.sizes.medium.file;
                    jQuery('.ecwid-post-id-' + data.post + ' .ecwid-blog-post-image').css('background-image', 'url(' + imageUrl + ')');
                    
                    for (var i = 0; i < window.ecwidBlogPosts.length; i++) if (window.ecwidBlogPosts[i].id == data.post ) {
                        window.ecwidBlogPosts[i].imageFile = data.media_details.sizes.medium.file;
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
            var post = jQuery('#ecwid_blog_feed .template-container .ecwid-blog-post').clone();
            post.addClass('ecwid-post-id-' + posts[i].id);
            jQuery('.ecwid-blog-post-link', post).attr('href', posts[i].link + '?utm_source=wpdashboard');
            jQuery('.ecwid-blog-post-title', post).html(posts[i].title);
            jQuery('.ecwid-blog-post-excerpt', post).html(posts[i].excerpt);
            
            if (posts[i].imageFile) {
                jQuery('.ecwid-blog-post-image', post).css('background-image', 'url(' + ecwidDashboardBlog.imagesCDN + posts[i].imageFile + ')');
            }
            
            post.appendTo('#ecwid_blog_feed .ecwid-blog-posts');
        }
    }
});