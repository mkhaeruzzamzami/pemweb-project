const API_URL = "http://localhost/pincela/api";


// === CREATE ===
export async function sendSupportForm(data) {
  const formData = new FormData();
  for (let key in data) {
    formData.append(key, data[key]);
  }

  const res = await fetch(`${API_URL}/lukisan/index.php`, {
    method: "POST",
    body: formData,
  });

  return await res.json();
}

// === READ ===
export async function getAllLukisan() {
  const res = await fetch(`${API_URL}/lukisan/index.php`);
  return await res.json();
}

// === UPDATE ===
export async function updateLukisan(data) {
  const formData = new FormData();
  for (let key in data) {
    formData.append(key, data[key]);
  }

  const res = await fetch(`${API_URL}/lukisan/update.php`, {
    method: "POST",
    body: formData,
  });

  return await res.json();
}

// === DELETE ===
export async function deleteLukisan(id) {
  const res = await fetch(`${API_URL}/lukisan/delete.php`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id }),
  });

  return await res.json();
}

// === LIKE ===
export async function sendLike(id) {
  const res = await fetch(`${API_URL}/lukisan/like.php`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id }),
  });

  return await res.json();
}

// === COMMENT ===
export async function sendComment(id, comment) {
  const res = await fetch(`${API_URL}/lukisan/comment.php`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      id: parseInt(id),
      comment,
    }),
  });

  return await res.json();
}
