<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Paws & Claws</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $doctorId = $_POST['doctor'];
    $mobile = $_POST['mobile'];
    $date = $_POST['date'];
    $user = $_SESSION['user_id'];

    // Database connection
    $con = mysqli_connect("localhost", "chutte", "PASSWORD") or die("Could not connect to MySQL Server");
    mysqli_select_db($con, "paws") or die("Could not select DB");

    // Prepare and execute the SQL query
    $query = "INSERT INTO bookings (doctor, user, mobile, date) VALUES ('$doctorId', '$user', '$mobile', '$date')";
    $result = mysqli_query($con, $query);

    // Check if the query was successful
    if ($result) {
        // Display success message and redirect to a success page
        //echo "Booking successful! Please check your appointments.";
        $message = "Booking successful! Please check your appointments.";
    } else {
        // Display error message
        //echo "Error occurred while booking. Please try again.";
        $message = "Error occurred while booking. Please try again.";
    }

    // Close the database connection
    mysqli_close($con);
}
?>


<body class="bg-gray-800">
    <nav class="border-gray-200 bg-gray-900">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
            <a href="/" class="flex items-center">
                <img src=".\assets\img\logos\logo-only.png" class="h-8 mr-3" alt="Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">Paws & Claws</span>
            </a>
            <div class="flex items-center">
                <p class="mr-6 text-sm  text-white hover:underline"><?php echo $_SESSION['user_name']; ?></p>
                <button class="text-sm text-blue-500 hover:underline"> <a href="logout.php" class="text-sm text-blue-500 hover:underline">Log Out</a></button>
            </div>
        </div>
    </nav>
    <nav class="bg-gray-700">
        <div class="max-w-screen-xl px-4 py-3 mx-auto">
            <div class="flex items-center">
                <ul class="flex flex-row font-medium mt-0 mr-6 space-x-8 text-sm">
                    <li>
                        <a href="/" class="text-white hover:underline" aria-current="page">Home</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <div class="flex my-3 flex-col">
        <h1 class="text-3xl text-white mx-auto">Welcome <?php echo $_SESSION['user_name']; ?></h1>
        <?php
        if (isset($message)) {
            echo '<p class="text-green-500 text-lg text-center">' . $message . '</p>';
        }
        ?>
        <h2 class="text-3xl text-white mx-auto">Book an Appointment</h2>
        <form action="#" method="post" class="flex flex-col w-full gap-4 mx-auto m-5">

            <div class="flex gap-4 w-1/2 mx-auto">
                <label for="doctor" class="block mb-2 text-lg my-auto font-medium text-white">Select an
                    option</label>
                <select id="doctor" name="doctor" required class="border text-sm rounded-lg block w-2/3 p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500">
                    <option selected>Choose a Doctor</option>
                    <?php
                    //connect to MYSQl Server
                    $con = mysqli_connect("localhost", "chutte", "PASSWORD") or die("Could not connect to MySQL Server");
                    //select DB
                    mysqli_select_db($con, "paws") or die("Could not select DB");
                    //select table
                    $result = mysqli_query($con, "SELECT * FROM doctors") or die("Could not select table");
                    // filed names -> id, name, mobile, status
                    // Loop through the result set
                    while ($row = mysqli_fetch_assoc($result)) {
                        $doctorId = $row['id'];
                        $doctorName = $row['name'];
                        echo '<option value="' . $doctorId . '">' . $doctorName . '</option>';
                    }

                    // Close the database connection
                    mysqli_close($con);
                    ?>
                    ?>
                </select>
            </div>

            <div class="flex gap-4 w-1/2 mx-auto">
                <label for="countries" class="block mb-2 text-lg my-auto font-medium text-white">Date: </label>
                <input type="date" name="date" id="date" class="bg-gray-700 text-white w-2/3" required>
            </div>

            <div class="flex gap-4 w-1/2 mx-auto">
                <label for="first_name" class="block mb-2 text-lg font-medium text-white">Mobile</label>
                <input type="text" id="mobile" name="mobile" class="border  text-sm rounded-lg  block p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="78xxxxxxx" required>
            </div>

            <button type="submit" class="w-40 mx-auto focus:ring-4 outline-green-600 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2 border-green-500 text-green-500 hover:text-white hover:bg-green-600 focus:ring-green-800">Book</button>

        </form>
    </div>

    <div class="w-2/3 mx-auto mb-11">
        <h3 class="text-white text-lg font-bold">Previous Bookings</h3>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left  text-gray-400">
                <thead class="text-xs uppercase  bg-gray-700 text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Doctor
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Mobile
                        </th>

                    </tr>
                </thead>
                <tbody>
    <?php
    // Connect to the MySQL server
    $con = mysqli_connect("localhost", "chutte", "PASSWORD") or die("Could not connect to MySQL Server");
    mysqli_select_db($con, "paws") or die("Could not select DB");

    // Fetch data from the bookings table
    $userId = $_SESSION['user_id'];
    $result = mysqli_query($con, "SELECT * FROM bookings WHERE user = $userId;") or die("Could not fetch data");

    // Loop through the result set and display the data
    while ($row = mysqli_fetch_assoc($result)) {
        $date = $row['date'];
        $doctorId = $row['doctor'];
        $mobile = $row['mobile'];

        // Retrieve doctor's name from the doctors table
        $doctorResult = mysqli_query($con, "SELECT name FROM doctors WHERE id = '$doctorId'");
        $doctorRow = mysqli_fetch_assoc($doctorResult);
        $doctorName = $doctorRow['name'];
        ?>

        <tr class="border-b border-gray-700 hover:bg-gray-600">
            <td class="px-6 py-4 font-medium whitespace-nowrap text-white">
                <?php echo $date; ?>
            </td>
            <td class="px-6 py-4">
                <?php echo $doctorName; ?>
            </td>
            <td class="px-6 py-4">
                <?php echo $mobile; ?>
            </td>
        </tr>

    <?php
    }

    // Close the database connection
    mysqli_close($con);
    ?>
</tbody>
            </table>
        </div>

    </div>


    <footer class="bg-white rounded-lg shadow m-4 bg-gray-800">
        <div class="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between">
            <span class="text-sm text-gray-500 sm:text-center text-gray-400">© 2023 <a href="#" class="hover:underline">Paws & Claws™</a>. All Rights Reserved.
            </span>
            <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 text-gray-400 sm:mt-0">
                <li>
                    <a href="#" class="mr-4 hover:underline md:mr-6 ">About</a>
                </li>
                <li>
                    <a href="#" class="hover:underline">Contact</a>
                </li>
            </ul>
        </div>
    </footer>


</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/datepicker.min.js"></script>
<script>
    //setting theme dark
    document.addEventListener('DOMContentLoaded', function() {
        document.documentElement.setAttribute('data-theme', 'dark');
    });
</script>

</html>