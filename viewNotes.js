// Function to handle adding a new note
document.getElementById('addNoteButton').addEventListener('click', function () {
    const noteContent = document.getElementById('noteContent').value.trim();

    if (noteContent === '') {
        alert('Note content cannot be empty.');
        return;
    }

    const formData = new FormData();
    formData.append('note_content', noteContent);
    formData.append('ajax', '1');

    fetch('viewnotes.php?topic_id=' + new URLSearchParams(window.location.search).get('topic_id'), {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the notes list
            const notesList = document.getElementById('notesList');
            notesList.innerHTML = ''; // Clear the list
            data.notes.forEach(note => {
                const li = document.createElement('li');
                li.innerHTML = `<strong>${note.user_name}:</strong> ${note.note_content} <em>(${note.date_added})</em>`;
                notesList.appendChild(li);
            });

            // Update the note count
            document.getElementById('noteCount').textContent = data.notes.length;

            // Reset the form
            document.getElementById('noteContent').value = '';
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
});
