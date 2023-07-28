async function fetchJSON(request) {
    try {
      const response = await fetch(request);
      const contentType = response.headers.get("content-type");
      if (!contentType || !contentType.includes("application/json")) {
        throw new TypeError("Oops, we haven't got JSON!");
      }
      return await response.json();
      // process your data further
    } catch (error) {
      console.error("Error:", error);
    }
  }

