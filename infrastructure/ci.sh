application_image_path=../application

function build_and_deploy() {
  cd $application_image_path
  docker login -u $DOCKER_HUB_USER -p $DOCKER_HUB_PASSWORD
  docker build -t $DOCKER_HUB_REPO:latest .
  docker push $DOCKER_HUB_REPO:latest
}

build_and_deploy
