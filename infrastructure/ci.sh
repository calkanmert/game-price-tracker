application_image_path=$GITHUB_WORKSPACE/application

function build_and_deploy() {
  docker login -u $DOCKER_HUB_USER -p $DOCKER_HUB_PASSWORD
  docker build -t $DOCKER_HUB_REPO:latest $application_image_path
  docker push $DOCKER_HUB_REPO:latest
}

build_and_deploy
