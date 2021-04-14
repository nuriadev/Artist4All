import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';
import { NgxSpinnerService } from 'ngx-spinner';
import { PublicationService } from 'src/app/core/services/publication.service';
import { Publication } from 'src/app/core/models/publication';
import { Notification } from 'src/app/core/models/notification';
import { User } from 'src/app/core/models/user';
import { NotificationService } from 'src/app/core/services/notification.service';
import {
  MatSnackBar,
  MatSnackBarHorizontalPosition,
  MatSnackBarVerticalPosition,
} from '@angular/material/snack-bar';
@Component({
  selector: 'app-profile',
  templateUrl: './index-profile.component.html',
  styleUrls: ['./index-profile.component.css'],
  providers: [UserService],
})
export class ProfileComponent implements OnInit {
  constructor(
    private _sessionService: SessionService,
    private _userService: UserService,
    private _publicationService: PublicationService,
    private _notificationService: NotificationService,
    private _activeRoute: ActivatedRoute,
    private spinner: NgxSpinnerService,
    private _snackBar: MatSnackBar
  ) {}

  publications: Array<Publication> = [];

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();

  id: number;
  name: string;
  surname1: string;
  surname2: string;
  email: string;
  username: string;
  password: string;
  imgAvatar: FileList;
  aboutMe: string;
  isPrivate: number;
  n_followers: number;
  n_followed: number;

  id_user: string = '';
  isMyProfile: boolean = false;
  loaded: boolean;
  id_follow: number;
  status_follow: number;
  ngOnInit(): void {
    this.loaded = false;
    this._activeRoute.paramMap.subscribe((params) => {
      this.spinner.show();
      setTimeout(() => {
        this.spinner.hide();
        this.loaded = true;
      }, 1500);
      this.id_user = params.get('id_user');
      if (parseInt(this.id_user) == this.user.id) {
        setTimeout(() => {
          this.id = this.user.id;
          this.name = this.user.name;
          this.surname1 = this.user.surname1;
          this.surname2 = this.user.surname2;
          this.email = this.user.email;
          this.username = this.user.username;
          this.password = this.user.password;
          this.imgAvatar = this.user.imgAvatar;
          this.aboutMe = this.user.aboutMe;
          this.isPrivate = this.user.isPrivate;
          this.isMyProfile = true;
          this.getFollowersAndFollowed(this.id, this.token);
          this.getUserPublications(this.id, this.token);
        }, 1200);
      } else {
        this._userService
          .getUserById(parseInt(this.id_user), this.token)
          .subscribe(
            (result) => {
              this.id = result.id;
              this.name = result.name;
              this.surname1 = result.surname1;
              this.surname2 = result.surname2;
              this.email = result.email;
              this.username = result.username;
              this.imgAvatar = result.imgAvatar;
              this.aboutMe = result.aboutMe;
              this.isPrivate = result.isPrivate;
              this.isMyProfile = false;
              this._userService
                .isFollowingThatUser(this.user.id, this.id, this.token)
                .subscribe(
                  (result) => {
                    if (result != null) {
                      this.id_follow = result.id;
                      this.status_follow = result.status_follow;
                    } else {
                      this.id_follow = null;
                      this.status_follow = 1;
                    }
                  },
                  (error) => {
                    console.log(error);
                  }
                );
              this.getFollowersAndFollowed(this.id, this.token);
              this.getUserPublications(this.id, this.token);
            },
            (error) => {
              console.log(error);
            }
          );
      }
    });
  }

  deletePublication(index: number) {
    this._publicationService
      .delete(this.user.id, this.publications[index].id, this.token)
      .subscribe(
        (result) => {
          location.reload();
        },
        (error) => {
          console.log(error);
        }
      );
  }

  n_likes: number;
  n_comments: number;
  id_like: number;
  likePublication(index: number) {
    this.isLiked = true;
    this.n_likes++;
    this._publicationService
      .addLike(this.publications[index], this.user.id, this.id, this.token)
      .subscribe(
        (result) => {
          this.id_like = result;
          // TODO: snackbar on click
        },
        (error) => {
          console.log(error);
        }
      );
  }

  removeLikePublication(index: number) {
    this.isLiked = false;
    this.n_likes--;
    this._publicationService
      .removelike(this.publications[index], this.id, this.token)
      .subscribe(
        (result) => {
          //TODO: snackbar y pedir confirmación / pasar a patch
        },
        (error) => {
          console.log(error);
        }
      );
  }

  isLiked: boolean;
  isPublicationLiked(index: number) {
    let likeIcon = document.getElementById('likeIcon' + index);
    let notLikedIcon = document.getElementById('notLikedIcon' + index);
    if (!this.isLiked) {
      likeIcon.style.display = 'block';
      notLikedIcon.style.display = 'none';
      this.isLiked = true;
    } else {
      notLikedIcon.style.display = 'block';
      likeIcon.style.display = 'none';
      this.isLiked = false;
    }
  }

  requestOrFollowUser() {
    if (this.isPrivate == 0) {
      this.status_follow = 3;
      this.n_followers++;
      this.message = 'Añadido a seguidos.';
    } else {
      this.status_follow = 2;
      this.message = 'Petición de amistad enviada.';
    }
    this._userService
      .requestOrFollowUser(
        this.id_follow,
        this.user.id,
        this.id,
        this.status_follow,
        this.token
      )
      .subscribe(
        (result) => {
          this.id_follow = result.id;
        },
        (error) => {
          console.log(error);
        }
      );
  }

  updateFollowRequest() {
    if (this.status_follow == 3) {
      this.n_followers--;
      this.message = 'Eliminado de seguidos.';
    } else {
      this.status_follow = 1;
      this.message = 'Petición de amistad cancelada.';
    }
    this._userService
      .updateFollowRequest(
        this.id_follow,
        this.user.id,
        this.id,
        this.status_follow,
        this.token
      )
      .subscribe(
        (result) => {
          this.id_follow = result.id;
          this.openSnackBar(this.message);
        },
        (error) => {
          console.log(error);
        }
      );
  }

  isUserFollowed() {
    let followContainer = document.getElementById('followContainer');
    let pendingContainer = document.getElementById('pendingContainer');
    let unfollowContainer = document.getElementById('unfollowContainer');
    if (this.status_follow == 1) {
      followContainer.style.display = 'block';
      pendingContainer.style.display = 'none';
      unfollowContainer.style.display = 'none';
    } else if (this.status_follow == 2) {
      followContainer.style.display = 'none';
      pendingContainer.style.display = 'block';
      unfollowContainer.style.display = 'none';
    } else {
      followContainer.style.display = 'none';
      pendingContainer.style.display = 'none';
      unfollowContainer.style.display = 'block';
    }
  }

  followersList: Array<User> = [];
  followedList: Array<User> = [];
  getFollowersAndFollowed(id_user: number, token: string) {
    this._userService.getFollowers(id_user, token).subscribe(
      (result) => {
        if (result != null) {
          this.followersList = result;
          this.n_followers = this.followersList.length;
        } else {
          this.n_followers = 0;
        }
      },
      (error) => {
        console.error(error);
      }
    );
    this._userService.getFollowed(id_user, token).subscribe(
      (result) => {
        if (result != null) {
          this.followedList = result;
          this.n_followed = this.followedList.length;
        } else {
          this.n_followed = 0;
        }
      },
      (error) => {
        console.error(error);
      }
    );
  }

  getUserPublications(id_user: number, token: string) {
    this._publicationService.getUserPublications(id_user, token).subscribe(
      (result) => {
        if (result != null) {
          result.forEach((element) => {
            element.upload_date = this.adaptDateOfPublication(
              element.upload_date
            );
          });
        }
        this.publications = result;
      },
      (error) => {
        console.log(error);
      }
    );
  }

  message: string;
  horizontalPosition: MatSnackBarHorizontalPosition = 'right';
  verticalPosition: MatSnackBarVerticalPosition = 'bottom';
  openSnackBar(message: string) {
    this._snackBar.open(message, 'OK', {
      duration: 1000,
      horizontalPosition: this.horizontalPosition,
      verticalPosition: this.verticalPosition,
    });
  }

  adaptDateOfPublication(upload_date: string) {
    const days = [
      'Domingo',
      'Lunes',
      'Martes',
      'Miércoles',
      'Jueves',
      'Viernes',
      'Sábado',
    ];
    const months = [
      'Enero',
      'Febrero',
      'Marzo',
      'Abril',
      'Mayo',
      'Junio',
      'Julio',
      'Agosto',
      'Septiembre',
      'Octubre',
      'Noviembre',
      'Diciembre',
    ];
    let numberDay = new Date(upload_date).getDay();
    let nameDay = days[numberDay];
    let nameMonth = months[new Date(upload_date).getMonth()];
    let datetime = upload_date.split(' ');
    let date = datetime[0].split('-');
    let time = datetime[1].split(':');
    let year = date[0];
    let month = date[1];
    let day = date[2];
    let hourAndMin = time[0] + ':' + time[1];
    let adaptedDate =
      nameDay +
      ', ' +
      day +
      ' de ' +
      nameMonth +
      ' de ' +
      year +
      ', a las ' +
      hourAndMin;
    return adaptedDate;
  }
}
