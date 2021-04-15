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
              this.publications.forEach((element, index) => {
                console.log(this.publications[index]);
                this.isPublicationLiked(index, this.user.id, element);
              });
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
  status_like: number;
  likePublication(index: number) {
    this.isLiked = true;
  //  this.publications[index].n_likes++;
    this.status_like = 1;
    this._publicationService
      .likePublication(
        this.publications[index],
        this.user.id,
        this.id,
        this.status_like,
        this.token
      )
      .subscribe(
        (result) => {
          this.id_like = result.id;
          // TODO: snackbar on click
        },
        (error) => {
          console.log(error);
        }
      );
  }
  // TODO: que pueda cambiar de like a no like
  removeLike(index: number) {
    this.isLiked = false;
    //this.publications[index].n_likes--;
    let likeIcon = document.getElementById(index + 'likeIcon');
    likeIcon.style.color = 'rgba(156, 163, 175, var(--tw-text-opacity))';
    likeIcon.onmouseover = function () {
      likeIcon.style.color = 'rgba(107, 114, 128, var(--tw-text-opacity))';
    };
    likeIcon.onmouseout = function () {
      likeIcon.style.color = 'rgba(156, 163, 175, var(--tw-text-opacity))';
    };
    this.isLiked = false;
    this.status_like = 0;
    this._publicationService
      .updateLikeStatus(
        this.id_like,
        this.publications[index],
        this.user.id,
        this.id,
        this.status_like,
        this.token
      )
      .subscribe(
        (result) => {
          //TODO: snackbar / pasar a patch
          this.id_like = result.id;
        },
        (error) => {
          console.log(error);
        }
      );
  }

  isLiked: boolean;
  isPublicationLiked(
    index: number,
    my_id: number,
    publication: any
  ) {
    this._publicationService
      .isPublicationLiked(my_id, publication, this.token)
      .subscribe(
        (result) => {
          if (result == null) this.status_like = 0;
          else this.status_like = result.status_like;
          let likeIcon = document.getElementById(index + 'likeIcon');
          if (this.status_like == 0) {
            likeIcon.style.color = 'rgba(59, 130, 246, var(--tw-text-opacity))';
            likeIcon.onmouseover = function () {
              likeIcon.style.color =
                'rgba(29, 78, 216, var(--tw-text-opacity))';
            };
            likeIcon.onmouseout = function () {
              likeIcon.style.color =
                'rgba(59, 130, 246, var(--tw-text-opacity))';
            };
            this.isLiked = false;
          } else {
            likeIcon.style.color =
              'rgba(156, 163, 175, var(--tw-text-opacity))';
            likeIcon.onmouseover = function () {
              likeIcon.style.color =
                'rgba(107, 114, 128, var(--tw-text-opacity))';
            };
            likeIcon.onmouseout = function () {
              likeIcon.style.color =
                'rgba(156, 163, 175, var(--tw-text-opacity))';
            };
            this.isLiked = true;
          }
        },
        (error) => {
          console.log(error);
        }
      );
      console.log(this.status_like);
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
          // añadir snackbar
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
            // LE PASAMOS EL ID NUESTRO Y EL DEL USUARIO DEL PERFIL Y EL ID DE LA PUBLICACIÓN
            this.publications = result;
          });
        }
      },
      (error) => {
        console.log(error);
      }
    );
  }

  //#039be5
  message: string;
  horizontalPosition: MatSnackBarHorizontalPosition = 'right';
  verticalPosition: MatSnackBarVerticalPosition = 'bottom';
  openSnackBar(message: string) {
    this._snackBar.open(message, 'OK', {
      duration: 1000,
      horizontalPosition: this.horizontalPosition,
      verticalPosition: this.verticalPosition
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
