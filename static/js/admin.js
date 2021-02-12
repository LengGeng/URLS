function delete_url(id) {
    if (confirm('确认删除该短链？')) {
        document.location = 'index.php?delete_id=' + id;
    }
}

