<footer class="p-strip--light p-sticky-footer" id="footer">
    <div class="row">
        <div class="col-9">
            <h3><span style="font-weight: 800"><?php echo $_ENV['APP_NAME']; ?></span></h3><br>
            <dl>
                <dd>
                    <span class=""><span class="fa fa-connection footer__icon"></span>
                    <b><?php echo $_ENV['ENVIRONMENT']; ?></b>
                    </span>
                </dd>
                <dd>
                    <span class="fa fa-info footer__icon"></span> version<?php echo $_ENV['VERSION']; ?></b>
                    <span class="fa fa-dash footer__icon"></span>
                </dd>
            </dl>
        </div>
        <div class="col-3">
            <p>
                <a class="p-link--soft" href="#">Back to top<i class="p-icon--top"></i> <span class="fa fa-arrow-up footer__icon"></span></a>
            </p>
        </div>
    </div>
</footer>

<script>
    function toggleDropdown(toggle, open) {
        var parentElement = toggle.parentNode;
        var dropdown = document.getElementById(toggle.getAttribute('aria-controls'));
        dropdown.setAttribute('aria-hidden', !open);

        if (open) {
            parentElement.classList.add('is-active');
        } else {
            parentElement.classList.remove('is-active');
        }
    }

    function closeAllDropdowns(toggles) {
        toggles.forEach(function (toggle) {
            toggleDropdown(toggle, false);
        });
    }

    function handleClickOutside(toggles, containerClass) {
        document.addEventListener('click', function (event) {
            var target = event.target;

            if (target.closest) {
                if (!target.closest(containerClass)) {
                    closeAllDropdowns(toggles);
                }
            }
        });
    }

    function initNavDropdowns(containerClass) {
        var toggles = [].slice.call(document.querySelectorAll(containerClass + ' [aria-controls]'));

        handleClickOutside(toggles, containerClass);

        toggles.forEach(function (toggle) {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();

                const shouldOpen = !toggle.parentNode.classList.contains('is-active');
                closeAllDropdowns(toggles);
                toggleDropdown(toggle, shouldOpen);
            });
        });
    }
    initNavDropdowns('.p-navigation__item--dropdown-toggle')
</script>

<script src="<?php echo $_ENV['ORIGIN']; ?>/js/logout.js"></script>
<script>
    let api = "<?php echo $_ENV['SITE_URL']; ?>";
    let site = "<?php echo $_ENV['ORIGIN']; ?>" + "/";
    <?php if ($this->Session->Authenticated()) { ?>
        Logout.init(api, site, document.getElementById("logout"));
    <?php } ?>
</script>