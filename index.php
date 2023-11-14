<?php
    require './vendor/autoload.php';
    use Orhanerday\OpenAi\OpenAi;

    $dotenv = Dotenv\Dotenv::createMutable(__DIR__);
    $dotenv->load();


    function summarizeText($text) {
        $apiKey = $_ENV['OPENAI_API_KEY'];
        $open_ai = new OpenAi($apiKey);
        //set api data
        $complete = $open_ai -> completion ([
            'model' => "gpt-3.5-turbo-instruct",
            'prompt' => "Summarize this text for me : $text",
            'temperature' => 0.7,
            'max_tokens' => 256,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
        ]);



        return json_decode($complete, true);
    }

    if(isset($_POST['submit'])){
          if(isset($_POST['text'])){
            $text = $_POST['text'];
            $data = summarizeText($text);
            // print_r($data);
          } elseif (isset($_FILES['file'])) {
                $file = $_FILES['file'];

                $uploadDir = "uploads/";  // Specify the directory where you want to save the uploaded files
                $uploadPath = $uploadDir . basename($file['name']);

                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $myfile = fopen($uploadPath, "r") or die("Unable to open file!");
                    $text = fread($myfile, filesize($uploadPath));
                    $data = summarizeText($text);
                    fclose($myfile);
                    $uploaded  =true;
                } else {
                    echo "Error uploading file.";
                }
            }
      }

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Text Summarizer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="stylesheet.css"> -->
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

body {
  /* padding: 20px; */
  text-align: center;
  font-family: Poppins;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: max-content;
}

h1 {
    font-family: Poppins;
    font-weight: 800;
    color:#ef5b25;
    font-size: 60px;
}

p {
  margin-bottom: 20px;
  font-size: 14px;
  width: 65%;
}

.container {
  display: flex;
  gap: 40px;
  justify-content: center;
  padding: 20px;
}

.text-box {
  max-height: 500px;
  width: 400px;
  border-radius: 4px;
  box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
  display: flex;
  flex-direction: column;
  padding: 20px;
  background-color: #FFFF;
}

textarea {
  height: 300px;
  resize: none;
  border: none;
  padding: 5px;
  margin-bottom: 10px;
  outline: none;
}

.submit-button {
  background-color: #ef5b25;
  color: #ffffff;
  border-radius: 4px;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  margin-bottom: 10px;
}

.submit-button-text {
  color: #ffffff;
}


#summary {
  margin-bottom: 20px;
  font-size: 14px;
  width: unset;
}

input[type="file"] {
    display: none;
}

.custom-file-upload {
  background-color: #ef5b25;
  color: #ffffff;
  border-radius: 4px;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  margin-bottom: 40px;
}

@media screen and (max-width: 768px) {
  .container {
    flex-direction: column;
  }
  h1 {
    font-family: Poppins;
    font-weight: 800;
    color:#ef5b25;
    font-size: 40px;
}

  p {
  margin-bottom: 20px;
  font-size: 14px;
  width: 90%;
}

.text-box {
  min-height: 400px;
  width: 100%;
  border-radius: 4px;
  box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
  display: flex;
  flex-direction: column;
  padding: 20px;
  background-color: #FFFF;
}
}


</style>


<body>
    <h1>AI Text Summarizer App</h1>
    <p> Welcome to my AI Text Summarizer App! This app leverages the power of Artificial Intelligence APIs to provide concise summaries of long texts. Whether you have a lengthy article, research paper, or any other text document that you want to summarize quickly, our app can assist you.</p>
    <p> Simply paste your text into the text area below or upload a text file and click the "Submit" button. </p>
    <div class="container">

        <form action="index.php" method="POST" class="text-box" enctype="multipart/form-data">
            <textarea name="text" placeholder = "<?php echo isset($placeholder_err) ? $placeholder_err : 'Input some text to summarize...'; ?>" ></textarea>

            <span>OR</span>

            <label for="file-upload" class="custom-file-upload">
              <input type="file" id="file-upload" class="submit-button" name="file">
              Upload a file
            </label>

            <button id="submit-button" class="submit-button" type="submit" name="submit" >
                <span class="submit-button-text">Summarize</span>
            </button>
        </form>

      <div class="text-box">
        <p id="summary" name="summarized_text">
            <?php
            if (isset($data['choices']) && is_array($data['choices']))
            {
                foreach ($data['choices'] as $choice) {
                    if (isset($choice['text']))
                    {
                        echo $choice['text'];
                    }
                }
            } else {
              echo "Summarised text will appear here...";
            }
        ?>
        </p>
    </div>

    </div>
  </body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</html>
