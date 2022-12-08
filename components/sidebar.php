<!-- Sidebar content -->
<div class="col-lg-3 mb-4 mb-lg-0 px-lg-0 mt-lg-0">
            <div style="visibility: hidden; display: none; width: 285px; height: 801px; margin: 0px; float: none; position: static; inset: 85px auto auto;"></div><div data-settings="{&quot;parent&quot;:&quot;#content&quot;,&quot;mind&quot;:&quot;#header&quot;,&quot;top&quot;:10,&quot;breakpoint&quot;:992}" data-toggle="sticky" class="sticky" style="top: 85px;"><div class="sticky-inner">


              
            <?php
            echo "<a class='btn btn-lg btn-block btn-success rounded-0 py-4 mb-3 bg-op-6 roboto-bold' href='askquestion.php'>Ask Question</a>";
            ?>
              
              <div class="bg-white mb-3">
                <h5 class="px-3 py-4 op-5 m-0">
                  Browse By Topics
                </h5>
                <hr class="m-0">

                <div class="pos-relative px-3 py-3">
                <?php
                  #select each topic to diplay
                    $sql = "SELECT topic_name, TopicID FROM Topic";
                    $result = $con->query($sql);
                    while ($obj = $result->fetch_assoc()) {
                      $topic_name = $obj["topic_name"];
                      $TopicID = $obj["TopicID"];
                      echo "<form action='browse_by_topics.php' method='GET'>";
                      echo "<input type='hidden' name='TopicID' value='$TopicID'>";
                      echo "<input type='hidden' name='topicName' value='$topic_name'>";
                      echo "<button type='submit' class='btn btn-primary' style='margin:5px'>$topic_name</button>";
                      echo "</form>";
                    }
                    $con->close();
                ?>
                </div>
              </div>
            </div></div>
          </div>
