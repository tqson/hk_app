<style>
    /* Profile page styles */
    .profile-avatar-container {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto 20px;
    }

    .profile-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .profile-avatar:hover {
        border-color: rgba(17, 124, 192, 0.3);
    }

    .profile-avatar-upload {
        position: absolute;
        bottom: 0;
        right: 0;
    }

    .profile-avatar-upload .btn {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: all 0.3s;
    }

    .profile-avatar-upload .btn:hover {
        transform: scale(1.1);
    }

    .profile-stats {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }

    .profile-stat-value {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-color);
    }

    .profile-stat-label {
        font-size: 0.8rem;
        color: #6c757d;
    }
</style>
