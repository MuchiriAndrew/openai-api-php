<?php
       require __DIR__ . '/vendor/autoload.php';
       use Orhanerday\OpenAi\OpenAi;

    if(isset($_POST['submit'])){
        if($_POST['text']){
            $text = $_POST['text'];
            $open_ai = new OpenAi('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');
            //set api data
            $complete = $open_ai -> completion ([
                'model' => "gpt-3.5-turbo-instruct",
                'prompt' => "Summarize this text for me : $text",
                'temperature' => 0.7,
                'max_tokens' => 256,
                'top_p' => 1,
                'frequency_penalty' => 0,
                'presence_penalty' => 0,
                // 'stream' => true,
            ]);

            // var_dump($complete);
            // echo $complete;

            $data = json_decode($complete, true);
    } else {
        $placeholder_err =  "Please enter some text to summarize";
    }
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenAI API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <form action="index.php" method="POST">
            <textarea name="text" placeholder = "<?php echo isset($placeholder_err) ? $placeholder_err : 'Input some text to summarize...'; ?>" cols="30" rows="10"></textarea>
            <input class = "btn btn-primary mt-5" type="submit" name="submit">
        </form>
    </div>

    <div id="answer">
        <p>
            <?php
            if (isset($data['choices']) && is_array($data['choices'])) {
                // Extract and output the 'text' from each choice
                foreach ($data['choices'] as $choice) {
                    if (isset($choice['text'])) {
                        echo $choice['text'];
                    }
                }
            }
        ?>
        </p>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</html>
