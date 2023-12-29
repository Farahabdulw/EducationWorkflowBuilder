@php
    $containerFooter = isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact' ? 'container-xxl' : 'container-fluid';
@endphp

<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
    <div class="{{ $containerFooter }}">
        <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
            <div>
                All rights reserved
                ©
                <script>
                    document.write(new Date().getFullYear())
                </script>
            </div>
            <div class="d-none d-lg-inline-block">
                <a href="/offices" target="_blank" class="footer-link me-4">Offices</a>
                <a href="/users" class="footer-link me-4" target="_blank">Users</a>
                <a href="/forms" target="_blank" class="footer-link me-4">Forms</a>
                <a href="/courses" target="_blank" class="footer-link d-none d-sm-inline-block">Courses</a>
            </div>
        </div>
    </div>
</footer>
<!--/ Footer-->
