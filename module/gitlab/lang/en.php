<?php
$lang->gitlab = new stdclass;
$lang->gitlab->common            = 'GitLab';
$lang->gitlab->browse            = 'GitLab Browse';
$lang->gitlab->search            = 'Search';
$lang->gitlab->create            = 'Create GitLab';
$lang->gitlab->edit              = 'Edit GitLab';
$lang->gitlab->view              = 'View GitLab';
$lang->gitlab->bindUser          = 'Bind User';
$lang->gitlab->webhook           = 'Webhook';
$lang->gitlab->bindProduct       = 'Import Product';
$lang->gitlab->importIssue       = 'Import Issue';
$lang->gitlab->delete            = 'Delete GitLab';
$lang->gitlab->confirmDelete     = 'Do you want to delete this GitLab server?';
$lang->gitlab->gitlabAccount     = 'GitLab Account';
$lang->gitlab->zentaoAccount     = 'Zentao Account';
$lang->gitlab->bindingStatus     = 'Binding Status';
$lang->gitlab->notBind           = 'Not bind';
$lang->gitlab->binded            = 'Binded';
$lang->gitlab->bindedError       = 'The bound user has been deleted or modified. Please bind again.';
$lang->gitlab->bindDynamic       = '%s and Zentao user %s';
$lang->gitlab->serverFail        = 'Connect to GitLab server failed, please check the GitLab server.';
$lang->gitlab->lastUpdate        = 'Last Update';
$lang->gitlab->confirmAddWebhook = 'Are you sure about creating Webhook？';
$lang->gitlab->addWebhookSuccess = 'Webhook created successfully';
$lang->gitlab->failCreateWebhook = 'Failed to create Webhook, please view the log';
$lang->gitlab->placeholderSearch = 'Project name';

$lang->gitlab->browseAction         = 'GitLab List';
$lang->gitlab->deleteAction         = 'Delete GitLab';
$lang->gitlab->gitlabProject        = "GitLab Project";
$lang->gitlab->browseProject        = "GitLab Project List";
$lang->gitlab->browseUser           = "User List";
$lang->gitlab->browseGroup          = "Group List";
$lang->gitlab->browseBranch         = "GitLab Branch List";
$lang->gitlab->browseTag            = "GitLab Tag List";
$lang->gitlab->browseTagPriv        = "GitLab Tag protected List";
$lang->gitlab->gitlabIssue          = "GitLab Issue";
$lang->gitlab->zentaoProduct        = 'Zentao Product';
$lang->gitlab->objectType           = 'Type'; // task, bug, story
$lang->gitlab->manageProjectMembers = 'Manage project member';
$lang->gitlab->createProject        = 'Create GitLab project';
$lang->gitlab->editProject          = 'Eidt GitLab project';
$lang->gitlab->deleteProject        = 'Delete GitLab project';
$lang->gitlab->createGroup          = 'Create group';
$lang->gitlab->editGroup            = 'Edit group';
$lang->gitlab->deleteGroup          = 'Delete group';
$lang->gitlab->createUser           = 'Create user';
$lang->gitlab->editUser             = 'Edit user';
$lang->gitlab->deleteUser           = 'Delete user';
$lang->gitlab->createBranch         = 'Add branch';
$lang->gitlab->manageGroupMembers   = 'Manage group member';
$lang->gitlab->createWebhook        = 'Create Webhook';
$lang->gitlab->browseBranchPriv     = 'Protect branch';
$lang->gitlab->createBranchPriv     = 'Cerate branch protected';
$lang->gitlab->editBranchPriv       = 'Edit branch protected';
$lang->gitlab->deleteBranchPriv     = 'Delete branch protected';
$lang->gitlab->createTag            = 'Create Tag';
$lang->gitlab->deleteTag            = 'Delete tag';
$lang->gitlab->createTagPriv        = 'Create tag protected';
$lang->gitlab->editTagPriv          = 'Edit tag protected';
$lang->gitlab->deleteTagPriv        = 'Delete tag protected';

$lang->gitlab->id             = 'ID';
$lang->gitlab->name           = "GitLab Server";
$lang->gitlab->url            = 'Server URL';
$lang->gitlab->token          = 'Token';
$lang->gitlab->defaultProject = 'Default Project';
$lang->gitlab->private        = 'MD5 Verify';

$lang->gitlab->lblCreate     = 'Create GitLab Server';
$lang->gitlab->desc          = 'Description';
$lang->gitlab->tokenFirst    = 'When the Token is not empty, the Token will be used first';
$lang->gitlab->tips          = 'When using a password, please disable the "Prevent cross-site request forgery" option in the GitLab global security settings.';
$lang->gitlab->emptyError    = " cannot be empty";
$lang->gitlab->createSuccess = "Create success";
$lang->gitlab->mustBindUser  = 'You have not registered the GitLab account, please contact the administrator to register.';
$lang->gitlab->noAccess      = 'Permission denied';
$lang->gitlab->notCompatible = 'The current GitLab version is not compatible with ZenTao, please upgrade the GitLab version and try again';

$lang->gitlab->placeholder = new stdclass;
$lang->gitlab->placeholder->name        = '';
$lang->gitlab->placeholder->url         = "Please fill in the access address of the GitLab Server homepage, as: https://gitlab.zentao.net.";
$lang->gitlab->placeholder->token       = "Please fill in the access token of an account with root privileges.";
$lang->gitlab->placeholder->projectPath = "It should contain only letters, digits, underscore, hyphen and period.  It should not start with hypen, or end with .git or .atom.";

$lang->gitlab->noImportableIssues = "There are currently no issues available for import.";
$lang->gitlab->tokenError         = "The current token is not root rights.";
$lang->gitlab->tokenLimit         = "The current token has no admin privilege. Please regenerate one with root user in GitLab.";
$lang->gitlab->hostError          = "So the current GitLab server address is invalid or the current GitLab version is not compatible with ZenTao, please confirm that the current server can be accessed or contact the administrator to upgrade the GitLab version to %s or above and try again.";
$lang->gitlab->bindUserError      = "Can not bind users repeatedly %s";
$lang->gitlab->importIssueError   = "The execution to which this issue belongs is not selected.";
$lang->gitlab->importIssueWarn    = "There is a problem of import failure, you can try to import again.";

$lang->gitlab->accessLevels[10] = 'Guest';
$lang->gitlab->accessLevels[20] = 'Reporter';
$lang->gitlab->accessLevels[30] = 'Developer';
$lang->gitlab->accessLevels[40] = 'Maintainer';
$lang->gitlab->accessLevels[50] = 'Owner';

$lang->gitlab->apiError[0] = 'internal is not allowed in a private group.';
$lang->gitlab->apiError[1] = 'public is not allowed in a private group.';
$lang->gitlab->apiError[2] = 'is too short (minimum is 8 characters)';
$lang->gitlab->apiError[3] = "can contain only letters, digits, '_', '-' and '.'. Cannot start with '-', end in '.git' or end in '.atom'";
$lang->gitlab->apiError[4] = 'Branch already exists';
$lang->gitlab->apiError[5] = 'Failed to save group {:path=>["has already been taken"]}';
$lang->gitlab->apiError[6] = '403 Forbidden';

$lang->gitlab->errorLang[0] = 'You cannot set Internal as its Visibility Level, if it is private in GitLab.';
$lang->gitlab->errorLang[1] = 'You cannot set Public as its Visibility Level, if it is private in GitLab.';
$lang->gitlab->errorLang[2] = 'Password is too short (minimum is 8 characters)';
$lang->gitlab->errorLang[3] = 'It should contain only letters, digits, underscore, hyphen and period.  It should not start with hypen, or end with .git or .atom.';
$lang->gitlab->errorLang[4] = 'Branch already exists.';
$lang->gitlab->errorLang[5] = 'Failed to save group, path has already been taken.';
$lang->gitlab->errorLang[6] = $lang->gitlab->noAccess;

$lang->gitlab->project = new stdclass;
$lang->gitlab->project->id                         = "Project ID";
$lang->gitlab->project->name                       = "Project name";
$lang->gitlab->project->create                     = "Create GitLab project";
$lang->gitlab->project->edit                       = "Edit GitLab project";
$lang->gitlab->project->url                        = "Project URL";
$lang->gitlab->project->path                       = "Project slug";
$lang->gitlab->project->description                = "Project description";
$lang->gitlab->project->visibility                 = "Visibility Level";
$lang->gitlab->project->visibilityList['private']  = "Private(Project access must be granted explicitly to each user. If this project is part of a group, access will be granted to members of the group)";
$lang->gitlab->project->visibilityList['internal'] = "Internal(The project can be accessed by any logged in user except external users)";
$lang->gitlab->project->visibilityList['public']   = "Public(The project can be accessed without any authentication)";
$lang->gitlab->project->star                       = "Stars";
$lang->gitlab->project->fork                       = "Forks";
$lang->gitlab->project->mergeRequests              = "Merge Requests";
$lang->gitlab->project->issues                     = "Issues";
$lang->gitlab->project->tagList                    = "Topics";
$lang->gitlab->project->tagListTips                = "Separate topics with commas.";
$lang->gitlab->project->emptyNameError             = "Project name cannot be empty.";
$lang->gitlab->project->emptyPathError             = "Project slug cannot be empty.";
$lang->gitlab->project->confirmDelete              = 'Do you want to delete this GitLab project?';
$lang->gitlab->project->notbindedError             = 'GitLab user has not been bound, unable to modify permissions!';
$lang->gitlab->project->publicTip                  = 'The visible status of the current project will be modified to public, and the project can be accessed without any authentication in GitLab. ';

$lang->gitlab->user = new stdclass;
$lang->gitlab->user->id             = "User ID";
$lang->gitlab->user->name           = "Name";
$lang->gitlab->user->username       = "Username";
$lang->gitlab->user->email          = "Email";
$lang->gitlab->user->password       = "Password";
$lang->gitlab->user->passwordRepeat = "Repeat Password";
$lang->gitlab->user->projectsLimit  = "Projects limit";
$lang->gitlab->user->canCreateGroup = "Can create group";
$lang->gitlab->user->external       = "External";
$lang->gitlab->user->externalTip    = "External users cannot see internal or private projects unless access is explicitly granted. Also, external users cannot create projects, groups, or personal snippets.";
$lang->gitlab->user->bind           = "Zentao user";
$lang->gitlab->user->avatar         = "Avatar";
$lang->gitlab->user->skype          = "Skype";
$lang->gitlab->user->linkedin       = "Linkedin";
$lang->gitlab->user->twitter        = "Twitter";
$lang->gitlab->user->websiteUrl     = "Website url";
$lang->gitlab->user->note           = "Note";
$lang->gitlab->user->createOn       = "Created on";
$lang->gitlab->user->lastActivity   = "Last activity";
$lang->gitlab->user->create         = "Create GitLab user";
$lang->gitlab->user->edit           = "Edit GitLab user";
$lang->gitlab->user->emptyError     = "cannot be empty";
$lang->gitlab->user->passwordError  = "The second password is inconsistent!";
$lang->gitlab->user->bindError      = "The user has been bound!";
$lang->gitlab->user->confirmDelete  = 'Do you want to delete this GitLab user?';

$lang->gitlab->group = new stdclass;
$lang->gitlab->group->id                                      = "Group ID";
$lang->gitlab->group->name                                    = "Group name";
$lang->gitlab->group->path                                    = "Group URL";
$lang->gitlab->group->pathTip                                 = "Changing group URL can have unintended side effects.";
$lang->gitlab->group->description                             = "Group description";
$lang->gitlab->group->avatar                                  = "Group avatar";
$lang->gitlab->group->avatarTip                               = 'Max file size is 200 KB.';
$lang->gitlab->group->visibility                              = "Visibility level";
$lang->gitlab->group->visibilityList['private']               = "Private(The group and its projects can only be viewed by members)";
$lang->gitlab->group->visibilityList['internal']              = "Internal(The group and any internal projects can be viewed by any logged in user except external users)";
$lang->gitlab->group->visibilityList['public']                = "Public(The group and any public projects can be viewed without any authentication.)";
$lang->gitlab->group->permission                              = 'Permission';
$lang->gitlab->group->requestAccessEnabledTip                 = "Allow users to request access (if visibility is public or internal)";
$lang->gitlab->group->lfsEnabled                              = 'Large File Storage';
$lang->gitlab->group->lfsEnabledTip                           = "Allow projects within this group to use Git LFS(This setting can be overridden in each project)";
$lang->gitlab->group->projectCreationLevel                    = "Allowed to create projects";
$lang->gitlab->group->projectCreationLevelList['noone']       = "No one";
$lang->gitlab->group->projectCreationLevelList['maintainer']  = "Maintainers";
$lang->gitlab->group->projectCreationLevelList['developer']   = "Developers + Maintainers";
$lang->gitlab->group->subgroupCreationLevel                   = "Allowed to create projects";
$lang->gitlab->group->subgroupCreationLevelList['owner']      = "Owners";
$lang->gitlab->group->subgroupCreationLevelList['maintainer'] = "Maintainers";
$lang->gitlab->group->create                                  = "Create Group";
$lang->gitlab->group->edit                                    = "Edit Group";
$lang->gitlab->group->createOn                                = "Created on";
$lang->gitlab->group->members                                 = "Group Members";
$lang->gitlab->group->confirmDelete                           = 'Do you want to delete this GitLab group?';
$lang->gitlab->group->emptyError                              = "cannot be empty";
$lang->gitlab->group->manageMembers                           = 'Manage Group Members';
$lang->gitlab->group->memberName                              = 'Account';
$lang->gitlab->group->memberAccessLevel                       = 'Access Level';
$lang->gitlab->group->memberExpiresAt                         = 'Expiration time';
$lang->gitlab->group->repeatError                             = "Group members cannot be added repeatedly";
$lang->gitlab->group->publicTip                               = 'The visible status of the current group will be modified to public, and the group can be accessed without any authentication in GitLab. ';

$lang->gitlab->branch = new stdclass();
$lang->gitlab->branch->name                        = 'Branch name';
$lang->gitlab->branch->from                        = 'Create from';
$lang->gitlab->branch->create                      = 'Create';
$lang->gitlab->branch->lastCommitter               = 'Last Committer';
$lang->gitlab->branch->lastCommittedDate           = 'Last Committed Date';
$lang->gitlab->branch->accessLevel                 = "Branch protection list";
$lang->gitlab->branch->mergeAllowed                = "Merge Allowed";
$lang->gitlab->branch->pushAllowed                 = "Push Allowed";
$lang->gitlab->branch->placeholderSearch           = "Branch name";
$lang->gitlab->branch->placeholderSelect           = "Branch name";
$lang->gitlab->branch->confirmDelete               = 'Branch will be writable for developers. Are you sure?';
$lang->gitlab->branch->branchCreationLevelList[40] = "Maintainers";
$lang->gitlab->branch->branchCreationLevelList[30] = "Developers + Maintainers";
$lang->gitlab->branch->branchCreationLevelList[0]  = "No one";
$lang->gitlab->branch->emptyPrivNameError          = "Branch cannot be empty.";
$lang->gitlab->branch->issetPrivNameError          = "The protection branch already exists";

$lang->gitlab->tag = new stdclass();
$lang->gitlab->tag->name               = 'Tag name';
$lang->gitlab->tag->ref                = 'Create from';
$lang->gitlab->tag->lastCommitter      = 'Last Committer';
$lang->gitlab->tag->lastCommittedDate  = 'Last Committed Date';
$lang->gitlab->tag->placeholderSearch  = "Enter branch tag";
$lang->gitlab->tag->message            = 'Message';
$lang->gitlab->tag->emptyNameError     = "Name cannot be empty.";
$lang->gitlab->tag->emptyRefError      = "Create from cannot be empty.";
$lang->gitlab->tag->issetNameError     = "The tag already exists";
$lang->gitlab->tag->confirmDelete      = 'Do you want to delete this GitLab tag?';
$lang->gitlab->tag->protected          = 'Protected';
$lang->gitlab->tag->accessLevel        = 'Allow creation';
$lang->gitlab->tag->protectConfirmDel  = 'Do you want to delete this GitLab tag protected?';
$lang->gitlab->tag->emptyPrivNameError = 'Tag cannot be empty.';
$lang->gitlab->tag->issetPrivNameError = 'The protection tag already exists.';
