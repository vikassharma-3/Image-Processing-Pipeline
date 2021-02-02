from skimage.metrics import structural_similarity
import argparse
import os
import os.path
import imutils
import glob
import cv2
import pymysql
#import com.coremedia.iso.IsoFile
#import com.googlecode.mp4parser.authoring.samples.DefaultMp4SampleList

#IT FILTERS WARNINGS 
import warnings
warnings.filterwarnings("ignore")

#CONNECTING TO MySQL SERVER DATABASE
global connection
global cursor
connection = pymysql.connect(host="localhost",user="root",passwd="",database="azure" )
cursor = connection.cursor()

select = """SELECT username FROM video_file_details WHERE user_id = 2 """
cursor.execute(select)
user = cursor.fetchall()
user = user[0][0]

no_of_images = len(glob.glob1("Images/"+str(user)+"/","*.jpg"))

j=1
while(True):
    file_exists = os.path.exists('Images/'+str(user)+"/"+str(user)+str(j)+'.jpg')
    if(file_exists == True):
        i=1
        while(True):
            file_exists = os.path.exists('Images/'+str(user)+"/"+str(user)+str(i+1)+'.jpg')
            if(file_exists == True and j != i+1):
                # 3. Load the two input images
                imageA = cv2.imread("Images/"+str(user)+"/"+str(user)+str(j)+".jpg")
                imageB = cv2.imread("Images/"+str(user)+"/"+str(user)+str(i+1)+".jpg")

                # 4. Convert the images to grayscale
                grayA = cv2.cvtColor(imageA, cv2.COLOR_BGR2GRAY)
                grayB = cv2.cvtColor(imageB, cv2.COLOR_BGR2GRAY)

                # 5. Compute the Structural Similarity Index (SSIM) between the two
                (score, diff) = structural_similarity(grayA, grayB, full=True)
                diff = (diff * 255).astype("uint8")
                score = round(score, 2)

                # 6. You can print only the score if you want
                ##print("SSIM of Image"+str(j)+" and Image"+str(i+1), end=" ")
                ##print(": {}".format(score))

                if(score >= 0.85):
                    os.remove("Images/"+str(user)+"/"+str(user)+str(i+1)+".jpg")
                    ##print("Image"+str(i+1)+" has been removed having similarity of "+str(score)+" with Image"+str(j))
                    i += 1
                    if(i+1 == j):
                        j += 1
                else:
                    i +=1

            else:
                i += 1
                
            if(i >= no_of_images):
                break

        j += 1
        
    else:
        j += 1

    if(j >= no_of_images):
        break


/*def decode(track: String) {
  val GPS_TRACK_IDX = 3
  val iso = new IsoFile(track)
  val samples: DefaultMp4SampleList = new DefaultMp4SampleList(GPS_TRACK_IDX, iso)
  val csv = (0 until samples.size).map(samples.get).map(
    sample => (
      (0 until sample.getSize.toInt).map("%02X" format sample.asByteBuffer.get(_)).mkString,
      sample.asByteBuffer.getShort(10),
      sample.asByteBuffer.getInt(14),
      sample.asByteBuffer.getInt(18),
      sample.asByteBuffer.getInt(14) / 11930464.711111112,
      sample.asByteBuffer.getInt(18) / 11930464.711111112)
  ).map(_.productIterator.mkString(",")).mkString("\n")
  println(csv)
}*/


