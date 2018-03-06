jQuery(document).ready(function() {
    if ( ecwidDashboardBlog.posts.length > 0 ) {
        renderBlogPosts(ecwidDashboardBlog.posts);
    } else {
        jQuery.getJSON( ecwidDashboardBlog.url, {}, function(data) {
            var posts = [], mediaIds = [];
            
            for (var i = 0; i < data.length; i++) {
                post = data[i];
                posts[i] = {
                    'title': post.title.rendered,
                    'excerpt': post.excerpt.rendered,
                    'link': post.link,
                    'id': post.id
                };
                
            }
            
            renderBlogPosts(posts);
            
            for (var i = 0; i < data.length; i++) {
                jQuery.getJSON( 'https://www.ecwid.com/wp-json/wp/v2/media/' + data[i].featured_media, function(data) {
                   jQuery( '.ecwid-post-id-' + data.post + ' img' ).attr('src', data.source_url);
                });
            }
        } );
    }
    
    function renderBlogPosts(posts) {
        for (var i = 0; i < posts.length; i++) {
            var post = jQuery('#ecwid_blog_feed .template-container .blog-post').clone();
            post.addClass('ecwid-post-id-' + posts[i].id);
            jQuery('.post-title', post).attr('href', posts[i].link);
            jQuery('.post-title', post).html(posts[i].title);
            jQuery('.post-excerpt', post).html(posts[i].excerpt);
            
            post.appendTo('#ecwid_blog_feed ul.posts');
        }
    }
});