const API_URL = "http://localhost/pincela/api";

// CREATE (Submit Lukisan Baru)
export async function sendSupportForm(data) {
  const formData = new FormData();
  for (let key in data) {
    formData.append(key, data[key]);
  }

  try {
    const response = await fetch(`${API_URL}/submit_support.php`, {
      method: "POST",
      body: formData,
    });

    return await response.json();
  } catch (error) {
    return { status: "error", message: error.message };
  }
}

// READ (Ambil Semua Data Lukisan)
export async function getAllLukisan() {
  try {
    const response = await fetch(`${API_URL}/read_support.php`);
    return await response.json();
  } catch (error) {
    return [];
  }
}

// UPDATE Lukisan
export async function updateLukisan(data) {
  const formData = new FormData();
  for (let key in data) {
    formData.append(key, data[key]);
  }

  try {
    const response = await fetch(`${API_URL}/update_support.php`, {
      method: "POST",
      body: formData,
    });

    return await response.json();
  } catch (error) {
    return { status: "error", message: error.message };
  }
}

// DELETE Lukisan
export async function deleteLukisan(id) {
  try {
    const response = await fetch(`${API_URL}/delete_support.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id }),
    });

    return await response.json();
  } catch (error) {
    return { status: "error", message: error.message };
  }
}
