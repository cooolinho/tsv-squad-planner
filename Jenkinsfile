def jenkinsRootDirectory = '$WORKSPACE'
def jenkinsBuildDirectory = '$WORKSPACE/build'
def jenkinsAppDirectory = '$WORKSPACE/symfony'

def remoteRootDirectory = '$REMOTE_ROOT_DIRECTORY'
def remoteAppDirectory = '$REMOTE_ROOT_DIRECTORY/symfony'
def remoteBuildDirectory = '$REMOTE_ROOT_DIRECTORY/build'
def environment = '$APP_ENV'

pipeline {
    agent any

    stages {
        stage('SCM Checkout') {
            steps {
                git branch: '$BRANCH_NAME', credentialsId: 'github', url: '$BRANCH_URL'
                archiveArtifacts artifacts: '**/*', excludes: 'vendor/', fingerprint: true, onlyIfSuccessful: true
            }
        }
        stage('Build') {
            steps {
                catchError {
                    sh "sh ${jenkinsBuildDirectory}/dependencies.sh ${jenkinsAppDirectory} $APP_ENV"
                    sh "sh ${jenkinsBuildDirectory}/assets.sh ${jenkinsAppDirectory} $APP_ENV"
                }
            }
            post {
                success {
                    echo 'Build successful'
                }
                failure {
                    echo 'Build failed'
                    error('Build is aborted due to failure of build')
                }
            }
        }
        stage('Deploy to Remote Server') {
            steps {
                sshagent(['$SSH_ID']) {
                    sh "rsync -r -a -v -e ssh -P -z --exclude=/.git --exclude=*/node_modules --exclude=*/var/cache  ${jenkinsRootDirectory}/ $SSH_USER@$SSH_HOST:${remoteRootDirectory}"
                }
            }
        }
        stage('Prepare Remote Server') {
            steps {
                sshagent(['$SSH_ID']) {
                    sh "ssh $SSH_USER@$SSH_HOST sh ${remoteBuildDirectory}/php-version.sh"
                    sh "ssh $SSH_USER@$SSH_HOST sh ${remoteBuildDirectory}/cache.sh ${remoteAppDirectory} $APP_ENV"
                    sh "ssh $SSH_USER@$SSH_HOST sh ${remoteBuildDirectory}/migrations.sh ${remoteAppDirectory} $APP_ENV"
                }
            }
        }
        stage('Delete Workspace') {
            steps {
                cleanWs()
            }
        }
    }
}
