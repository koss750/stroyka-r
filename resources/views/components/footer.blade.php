    <footer>
        <div class="container">
            <p>&copy; [Current Year] [Your Company Name]. All Rights Reserved.</p>
        </div>
    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const hamburger = document.querySelector(".hamburger");
            const menu = document.querySelector(".menu");

            hamburger.addEventListener("click", function () {
                hamburger.classList.toggle("open");
                menu.classList.toggle("open");
            });
        });
    </script>
    </body>
</html>