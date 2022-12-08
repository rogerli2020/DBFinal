<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href='homepage.php?'><strong><i>WebpageLogo</i></strong></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
            </ul>

            <?php
              $username = $_SESSION['username'];
              echo "<a class='nav-link' href='user_profile.php?ViewingUserID=$UserID'>$username</a>"
            ?>
            <form class="form-inline my-2 my-lg-0" action="search.php" method="GET">
            <?php             
              echo "<input type='hidden' name='order_by' value='Relevance'>";
            ?>
            <input name="keyword" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" required>
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
        </nav>
