<?php

require_once("auth.php");
require_once("../config/database.php");

$db = new Database();
$pdo = $db->connect();


/* ==========================================
   NEWS LADEN
========================================== */

$stmt = $pdo->query("
    SELECT
        n.id,
        n.title,
        n.slug,
        n.status,
        n.is_premium,
        n.show_slider,
        n.is_pinned,
        n.created_at,

        (
            SELECT ni.image
            FROM news_images ni
            WHERE ni.news_id = n.id
            ORDER BY
                ni.is_hero DESC,
                ni.sort_order ASC
            LIMIT 1
        ) AS image

    FROM news n

    ORDER BY n.created_at DESC, n.id DESC
");

$news = $stmt->fetchAll(PDO::FETCH_ASSOC);


include("header.php");

?>

<div class="admin-content">

    <div class="page-header">

        <div>

            <h1>News verwalten</h1>

            <p>
                Verwalte alle veröffentlichten Artikel
                und Entwürfe.
            </p>

        </div>

        <a
            href="news.php"
            class="save-btn">

            + Neue News

        </a>

    </div>


    <?php if (empty($news)): ?>

        <div class="form-group">

            <p>
                Noch keine News vorhanden.
            </p>

        </div>

    <?php else: ?>

        <div class="news-admin-list">

            <?php foreach ($news as $article): ?>

                <article class="news-admin-card">

                    <div class="news-admin-image">

                        <?php if (!empty($article["image"])): ?>

                            <img
                                src="../uploads/news/thumbnails/<?= htmlspecialchars(
                                    $article["image"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>"
                                alt="">

                        <?php else: ?>

                            <div class="news-admin-placeholder">
                                Kein Bild
                            </div>

                        <?php endif; ?>

                    </div>


                    <div class="news-admin-content">

                        <div class="news-admin-meta">

                            <span>
                                <?= htmlspecialchars(
                                    $article["status"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>
                            </span>

                            <?php if ((int) $article["is_premium"] === 1): ?>

                                <span>Premium</span>

                            <?php endif; ?>

                            <?php if ((int) $article["show_slider"] === 1): ?>

                                <span>Slider</span>

                            <?php endif; ?>

                        </div>


                        <h2>

                            <?= htmlspecialchars(
                                $article["title"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>

                        </h2>


                        <div class="news-admin-actions">

                            <a
                                href="news-edit.php?id=<?= (int) $article["id"] ?>"
                                class="secondary-btn">

                                Bearbeiten

                            </a>

                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

<?php include("footer.php"); ?>