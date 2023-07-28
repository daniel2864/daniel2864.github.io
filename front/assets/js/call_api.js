


async function fetchJSON(request) {
  const URL_API = 'http://localhost:8000/public/'
  
    try {
      const response = await fetch(URL_API+request);
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


  
