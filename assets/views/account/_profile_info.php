<?php $baseImgPath = $this->pixie->getParameter('parameters.use_external_dir') ? '/upload/download.php?image=' : '/user_pictures/'; ?>
<div class="row">
    <div class="col-xs-8">
        <table class="table profile-table table-striped">
            <thead>
                <tr>
                    <th scope="col">Field</th>
                    <th scope="col">Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">Username:</th>
                    <td><?php echo htmlspecialchars($userData['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <th scope="row">E-mail:</th>
                    <td><?php echo htmlspecialchars($userData['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <th scope="row">First Name:</th>
                    <td><?php echo htmlspecialchars($userData['first_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Last Name:</th>
                    <td><?php echo htmlspecialchars($userData['last_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Phone:</th>
                    <td><?php echo htmlspecialchars($userData['user_phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-xs-4">
        <?php if (isset($user->photo) && $user->photo): ?>
            <img src="<?php echo htmlspecialchars($baseImgPath . $user->getPhotoPath(), ENT_QUOTES, 'UTF-8'); ?>" alt="User Profile Picture" class="profile-picture img-responsive img-bordered img-thumbnail" />
        <?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <p class="text-right buttons-row">
            <a href="/account/profile/edit" id="profile_link" class="btn btn-primary ladda-button" data-style="expand-right"><span class="ladda-label">Edit Profile</span></a>
        </p>
    </div>
</div>
<script>
    $(function() {
        Ladda.bind('#profile_link');
        
        $('#profile_link').on('click', function(e) {
            var l = Ladda.create(document.querySelector('#profile_link'));
            l.start();
            window.location.href = "/account/profile/edit";
            return false; // Will stop the submission of the form
        });
    });
</script>
