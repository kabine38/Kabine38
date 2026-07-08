<?php

require_once("auth.php");
include("header.php");

?>

<h1 class="page-title">Dashboard</h1>

<p class="page-subtitle">
Willkommen zurück im KABINE38 CMS.
</p>

<div class="cards">

    <div class="card">
        <h3>📰 News</h3>
        <p>0</p>
    </div>

    <div class="card">
        <h3>🔥 Top News</h3>
        <p>0</p>
    </div>

    <div class="card">
        <h3>💎 Premium</h3>
        <p>0</p>
    </div>

    <div class="card">
        <h3>📦 Archiv</h3>
        <p>0</p>
    </div>

</div>

<div class="dashboard-grid">

    <div class="dashboard-box">

        <div class="box-header">

            <h2>Letzte News</h2>

            <a href="#" class="btn">
                + Neue News
            </a>

        </div>

        <table>

            <thead>

            <tr>

                <th>Titel</th>
                <th>Kategorie</th>
                <th>Status</th>

            </tr>

            </thead>

            <tbody>

            <tr>

                <td>Noch keine News vorhanden</td>

                <td>-</td>

                <td>-</td>

            </tr>

            </tbody>

        </table>

    </div>

    <div class="dashboard-box">

        <h2>Schnellzugriff</h2>

        <div class="quick-links">

            <a href="#">📰 Neue News</a>

            <a href="#">🏆 Top News</a>

            <a href="#">⚽ Herren</a>

            <a href="#">👦 Jugend</a>

            <a href="#">💎 Premium</a>

            <a href="#">📦 Archiv</a>

        </div>

    </div>

</div>

<?php

include("footer.php");

?>