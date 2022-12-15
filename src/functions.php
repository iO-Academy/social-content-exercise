<?php

function getDbConnection(): PDO
{
    $db = new PDO('mysql:host=db;dbname=social-content', 'root', 'password');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $db;
}

function getPosts(PDO $db): array
{
    $sql = 'SELECT * FROM `posts` ORDER BY `created` DESC;';
    $query = $db->prepare($sql);
    $query->execute();
    return $query->fetchAll();
}

/*
 * Only need this for the stretch goal
 * Do it this way, NOT WITH A JOIN!
 * Join would not work for multiple replies to a single post
 */
function getReplies(PDO $db, array $posts): array
{
    foreach($posts as $k => $post) {
        $sql = 'SELECT * FROM `replies` WHERE `reply_to` = ? ORDER BY `created` DESC;';
        $query = $db->prepare($sql);
        $query->execute([$post['id']]);
        $posts[$k]['replies'] = $query->fetchAll();
    }
    return $posts;
}

function outputPosts(array $posts)
{
    $output = '';
    foreach ($posts as $post) {
        $output .= outputPost($post);
    }
    return $output;
}

function outputPost(array $post)
{
    return '
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">' . $post['name'] . ' <small class="small mr-2 float-right text-muted">' . formatDate($post['created']) . '</small></h5>
            <p class="card-text">' . $post['post'] . '</p>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-info">-</button>
                <span class="disabled btn btn-outline-info">' . $post['upvotes'] . '</span>
                <button type="button" class="btn btn-info">+</button>
            </div>
        </div>
        ' . (!empty($post['replies']) ? outputReply($post) : "") . '
        
    </div>
    ';
}

function outputReply(array $post): string
{
    $replies = '';

    foreach ($post['replies'] as $reply) {
        $replies .= '
        <div class="card-body border-left border-info ml-4 mb-4">
            <h5 class="card-title">' . $reply['name'] . ' <small class="small mr-2 float-right text-muted">' . formatDate($reply['created']) . '</small></h5>
            <p class="card-text">' . $reply['reply'] . '</p>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-info">-</button>
                <span class="disabled btn btn-outline-info">' . $reply['upvotes'] . '</span>
                <button type="button" class="btn btn-info">+</button>
            </div>
        </div>
        ';
    }

    return $replies;
}

function formatDate(string $date): string
{
    return date('d/m/Y G:i:s', strtotime($date));
}