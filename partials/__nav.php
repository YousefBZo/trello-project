<nav>
    <div class="nav">
        <ul class="pipes">
            <li><a href="index.php" class="nav-link">Home</a></li>
            <li><a href="setting.php" class="nav-link">Portofolio</a></li>
             <div class="board-search">
                <form action="" method="post">
                    <input type="search" id="searchInput" list="boardList" autocomplete="off" name="boardSearch"
                        class="board-search-input" aria-label="Board Search">
                    <i class="fas fa-search search-icon" aria-hidden="true"></i>
                </form>
            </div>
            <datalist id="boardList">
                <?php
                $boards = $board->find([]);
                foreach ($boards as $sBoard) {
                    $boardName = $sBoard['title']; // Corrected variable name here
                    echo "<option value='$boardName'>$boardName</option>";
                }
                ?>
            </datalist>
            <?php
            if (isset($_POST['boardSearch'])) {
                $boardSearch = $board->checkFields($_POST['boardSearch']);
                $boardSearchSlug = $board->create_slug($boardSearch);
                header("Location:board.php?slug=$boardSearchSlug");
                exit();
            }
            ?>

        </ul>
    </div>
</nav>