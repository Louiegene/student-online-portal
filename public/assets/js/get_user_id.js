async function getUserId() {
    try {
        const response = await fetch('../../src/controllers/get_user_id.php');
        const data = await response.json();

        if (data.user_id) {
            return data.user_id;
            console.log('getUserId: User ID:', data.user_id);
        } else {
            console.warn('getUserId: User not logged in.');
            return null;
        }
    } catch (error) {
        console.error('getUserId: Failed to fetch user ID', error);
        return null;
    }
}
