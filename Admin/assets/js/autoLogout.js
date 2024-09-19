var inactivityTime = function () {
    var time;
    var logoutTime = 3 * 60 * 1000; // 3 minutes

    function resetTimer() {
        clearTimeout(time);
        time = setTimeout(logout, logoutTime); // Logout after 3 minutes
    }

    function logout() {
        window.location.href = 'logout.php'; // Redirect to logout page
    }

    // Events to detect user activity
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.ontouchstart = resetTimer;
    document.onclick = resetTimer;
    document.onkeydown = resetTimer; // Detect key presses
};

inactivityTime();