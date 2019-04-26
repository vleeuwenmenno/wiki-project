-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2018 at 10:50 AM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stardebris.net`
--

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `languageId` int(11) NOT NULL,
  `languageName` varchar(256) NOT NULL,
  `languageIcon` varchar(256) NOT NULL,
  `languageDeveloper` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`languageId`, `languageName`, `languageIcon`, `languageDeveloper`) VALUES
(1, 'C# (C Sharp)', 'devicon-csharp-plain', 'Microsoft'),
(8, 'Python 3', 'devicon-python-plain', 'Guido van Rossum');

-- --------------------------------------------------------

--
-- Table structure for table `languagetags`
--

CREATE TABLE `languagetags` (
  `id` int(11) NOT NULL,
  `tagId` int(11) NOT NULL,
  `languageId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `languagetags`
--

INSERT INTO `languagetags` (`id`, `tagId`, `languageId`) VALUES
(17, 1, 1),
(18, 4, 1),
(19, 2, 1),
(20, 16, 6),
(21, 17, 7),
(22, 2, 3),
(23, 5, 3),
(26, 6, 2),
(27, 3, 2),
(28, 2, 2),
(29, 25, 4),
(30, 4, 4),
(33, 7, 8);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(512) NOT NULL,
  `salt` varchar(512) NOT NULL,
  `userType` varchar(64) NOT NULL DEFAULT 'user',
  `activateSecret` varchar(256) NOT NULL,
  `recoverySecret` varchar(256) NOT NULL,
  `mod_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tagId` int(11) NOT NULL,
  `tagName` varchar(48) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tagId`, `tagName`) VALUES
(1, 'C#'),
(2, 'OOP'),
(3, 'Java'),
(4, 'Microsoft'),
(5, '.NET'),
(6, 'Oracle'),
(7, 'Python');

-- --------------------------------------------------------

--
-- Table structure for table `wikipage`
--

CREATE TABLE `wikipage` (
  `wikiPageId` int(11) NOT NULL,
  `languageId` int(11) NOT NULL,
  `pageIndex` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wikipage`
--

INSERT INTO `wikipage` (`wikiPageId`, `languageId`, `pageIndex`) VALUES
(13, 1, 0),
(19, 1, 1),
(24, 8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `wikipagedetails`
--

CREATE TABLE `wikipagedetails` (
  `wikiPageId` int(11) NOT NULL,
  `pageOwner` int(11) NOT NULL,
  `pageTitle` varchar(256) NOT NULL,
  `pageContent` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wikipagedetails`
--

INSERT INTO `wikipagedetails` (`wikiPageId`, `pageOwner`, `pageTitle`, `pageContent`) VALUES
(13, 50, 'C# Console App', '### To create and run a console application  \n  \n1.  Start Visual Studio.  \n  \n2.  On the menu bar, choose **File**, **New**, **Project**.  \n  \n     The **New Project** dialog box opens.  \n  \n3.  Expand **Installed**, expand **Templates**, expand **Visual C#**, and then choose **Console Application**.  \n  \n4.  In the **Name** box, specify a name for your project, and then choose the **OK** button.  \n  \n     The new project appears in **Solution Explorer**.  \n  \n5.  If Program.cs isn\'t open in the **Code Editor**, open the shortcut menu for **Program.cs** in **Solution Explorer**, and then choose **View Code**.  \n  \n6.  Replace the contents of Program.cs with the following code.  \n  \n  ```csharp\n  // A Hello World! program in C#.\n  using System;\n  namespace HelloWorld\n  {\n    class Hello \n    {\n      static void Main() \n      {\n        Console.WriteLine(\"Hello World!\");\n\n        // Keep the console window open in debug mode.\n        Console.WriteLine(\"Press any key to exit.\");\n        Console.ReadKey();\n      }\n    }\n  }\n  \n  \n7.  Choose the F5 key to run the project. A Command Prompt window appears that contains the line Hello World!  \n  \n Next, the important parts of this program are examined.  '),
(17, 50, 'Test', '# Test Stuff\nAmazing test stuff\n```csharp\nnamespace Lol.Help.Me\n{\n    class theEndIsNear\n    {\n        static void main(string[] args)\n        {\n            this.Nukes(900000);\n        }\n    }\n}\n```'),
(19, 50, 'GTK# UI Example', 'A Hello World program with Gtk#:\n\n``` csharp\nusing Gtk;\nusing System;\n\nclass Hello \n{\n    static void Main()\n    {\n        Application.Init ();\n\n        Window window = new Window (\"helloworld\");\n        window.Show();\n\n        Application.Run ();\n    }\n}\n```\n\nTo compile this code:\n\n``` bash\nmcs helloworld.cs -pkg:gtk-sharp-2.0\n```\n\nIt\'s a bit longer than console hello world and needs some explanation.\n\nEvery Gtk# application must import the Gtk namespace:\n\n``` csharp\nusing Gtk;\n```\n\nIf you don\'t do so, each class from one of these namespaces will need the namespace mentioned as a prefix.\n\nNow, let\'s walk through Main().\n\n``` csharp\nApplication.Init()\n```\n\nThis initializes GTK and is needed in every Gtk# application.\n\nNow, create a Window object; the first parameter is the title of the window. To display it, call Show().\n\n``` csharp\nWindow window = new Window (\"helloworld\");\nwindow.Show();\n```\n\nNow that the window is drawn, run the main appliction loop, which displays everything and waits for events. This loop will run until Quit() is called.\n\n``` csharp\nApplication.Run()\n```\n\nNote that you\'ll need to exit this program with CTRL-C, as it doesn\'t have code to handle quitting more elegantly. For how to fix this, read on.\n\nHelloWorld, second try\n----------------------\n\nHere\'s a more elegant version of the above. It knows how to quit elegantly, and we\'ll use it to introduce *event handling*in Gtk#.\n\n``` csharp\n        using Gtk;\n        using System;\n \n        class Hello {\n \n                static void Main()\n                {\n                        Application.Init ();\n \n                    // Set up a button object.\n                        Button btn = new Button (\"Hello World\");\n                        // when this button is clicked, it\'ll run hello()\n                        btn.Clicked += new EventHandler (hello);\n \n                        Window window = new Window (\"helloworld\");\n                    // when this window is deleted, it\'ll run delete_event()\n                        window.DeleteEvent += delete_event;\n \n                    // Add the button to the window and display everything\n                    window.Add (btn);\n                        window.ShowAll ();\n \n                        Application.Run ();\n                }\n \n \n            // runs when the user deletes the window using the \"close\n            // window\" widget in the window frame.\n                static void delete_event (object obj, DeleteEventArgs args)\n                {\n                            Application.Quit ();\n                }\n \n                // runs when the button is clicked.\n                static void hello (object obj, EventArgs args)\n                {\n                        Console.WriteLine(\"Hello World\");\n                        Application.Quit ();\n                }\n        }\n```\n\nGtk# is an event driven toolkit, which means it will sleep in Application.Run() until an event occurs and control is passed to the appropriate function. Gtk# can make use of the type \"event\". When you close the \"HelloWorld\" window, the window throws an event of type DeleteEvent. To enable your application to react on the DeleteEvent, you must connect it to an event handler.\n\n``` csharp\nwindow.DeleteEvent += delete_event;\n```\n\nAn event handler gets passed four parameters: an object, the object that fired the event, the window, and an EventArgs object. In this case, the EventArgs have the special type DeleteEventArgs).\n\n``` csharp\n                static void delete_event (object obj, DeleteEventArgs args)\n                {\n                            Application.Quit ();\n                }\n```\n\nThis sample also adds a button to the window and connects the clicked event to \"hello\".'),
(24, 50, 'Hello world', '# Hello, World!\nPython is a very simple language, and has a very straightforward syntax. It encourages programmers to program without boilerplate (prepared) code. The simplest directive in Python is the \"print\" directive - it simply prints out a line (and also includes a newline, unlike in C).\n\n\nThere are two major Python versions, Python 2 and Python 3. Python 2 and 3 are quite different. This tutorial uses Python 3, because it more semantically correct and supports newer features.\n\n\nFor example, one difference between Python 2 and 3 is the print statement. In Python 2, the \"print\" statement is not a function, and therefore it is invoked without parentheses. However, in Python 3, it is a function, and must be invoked with parentheses.\n\nTo print a string in Python 3, just write:\n```\nprint(\"This line will be printed.\")\n```');

-- --------------------------------------------------------

--
-- Table structure for table `wikipagetags`
--

CREATE TABLE `wikipagetags` (
  `id` int(11) NOT NULL,
  `tagId` int(11) NOT NULL,
  `wikiPageId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`languageId`);

--
-- Indexes for table `languagetags`
--
ALTER TABLE `languagetags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tagId`);

--
-- Indexes for table `wikipage`
--
ALTER TABLE `wikipage`
  ADD PRIMARY KEY (`wikiPageId`);

--
-- Indexes for table `wikipagedetails`
--
ALTER TABLE `wikipagedetails`
  ADD PRIMARY KEY (`wikiPageId`);

--
-- Indexes for table `wikipagetags`
--
ALTER TABLE `wikipagetags`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `languageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `languagetags`
--
ALTER TABLE `languagetags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tagId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `wikipage`
--
ALTER TABLE `wikipage`
  MODIFY `wikiPageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `wikipagedetails`
--
ALTER TABLE `wikipagedetails`
  MODIFY `wikiPageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `wikipagetags`
--
ALTER TABLE `wikipagetags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
