<!-- Sidebar content -->
<div class="col-lg-3 mb-4 mb-lg-0 px-lg-0 mt-lg-0">
            <div style="visibility: hidden; display: none; width: 285px; height: 801px; margin: 0px; float: none; position: static; inset: 85px auto auto;"></div><div data-settings="{&quot;parent&quot;:&quot;#content&quot;,&quot;mind&quot;:&quot;#header&quot;,&quot;top&quot;:10,&quot;breakpoint&quot;:992}" data-toggle="sticky" class="sticky" style="top: 85px;"><div class="sticky-inner">


              
            <?php
            echo "<a class='btn btn-lg btn-block btn-success rounded-0 py-4 mb-3 bg-op-6 roboto-bold' href='askquestion.php?UserID=$UserID'>Ask Question</a>";
            ?>
              
              <div class="bg-white mb-3">
                <h5 class="px-3 py-4 op-5 m-0">
                  Browse By Topics
                </h5>
                <hr class="m-0">

                <div class="pos-relative px-3 py-3">
                <?php
                    $sql = "SELECT topic_name, TopicID FROM Topic";
                    $result = $con->query($sql);
                    while ($obj = $result->fetch_assoc()) {
                      $topic_name = $obj["topic_name"];
                      $TopicID = $obj["TopicID"];
                      echo "<form action='browse_by_topics.php' method='GET'>";
                      echo "<input type='hidden' name='TopicID' value='$TopicID'>";
                      echo "<input type='hidden' name='UserID' value='$UserID'>";
                      echo "<input type='hidden' name='topicName' value='$topic_name'>";
                      echo "<button type='submit' class='btn btn-primary' style='margin:5px'>$topic_name</button>";
                      echo "</form>";
                    }
                    $con->close();
                ?>
                </div>
                <!-- ELEMENT START -->
                <!-- <hr class="m-0">
                <div class="pos-relative px-3 py-3">
                  <h6 class="text-primary text-sm">
                    <a href="#" class="text-primary">Why Bootstrap 4 is so awesome? </a>
                  </h6>
                  <p class="mb-0 text-sm"><span class="op-6">Posted</span> <a class="text-black" href="#">39 minutes</a> <span class="op-6">ago by</span> <a class="text-black" href="#">AppStrapMaster</a></p>
                </div> -->
                <!-- ELEMENT END -->

              </div>
            </div></div>
          </div>