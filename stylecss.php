body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 20px;
    line-height: 1.6;
}

h1 {
    text-align: center;
    color: #007BFF;
    margin-bottom: 20px;
}

.rules {
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.rules h2 {
    margin-top: 0;
}

.rules ul {
    padding-left: 20px;
}

button {
    background-color: #007BFF;
    color: #fff;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 1rem;
}

button:hover {
    background-color: #0056b3;
}

input[type="text"], textarea {
    width: 100%;
    padding: 10px;
    margin: 5px 0 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1rem;
}

textarea {
    resize: vertical;
}

.content {
    margin-bottom: 20px;
}

.content h2 {
    margin-bottom: 10px;
}

.posts {
    background-color: #ffffff;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.posts h2 {
    margin-bottom: 15px;
}

.post {
    border-bottom: 1px solid #ddd;
    padding: 10px 0;
}

.post:last-child {
    border-bottom: none;
}

.post strong {
    color: #007BFF;
    font-size: 1.1rem;
}

.posts p {
    font-style: italic;
    color: #555;
}

@media (max-width: 600px) {
    body {
        padding: 10px;
    }

    button {
        width: 100%;
    }

    textarea, input[type="text"] {
        font-size: 0.9rem;
    }
}
