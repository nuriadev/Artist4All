import { AfterViewChecked, Component, ElementRef, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';
import { NgxSpinnerService } from 'ngx-spinner';
import { PublicationService } from 'src/app/core/services/publication.service';
import { Publication } from 'src/app/core/models/publication';
import { User } from 'src/app/core/models/user';
import { ViewChild } from '@angular/core';
import { Location } from '@angular/common';
import { MatSnackBar, MatSnackBarHorizontalPosition, MatSnackBarVerticalPosition } from '@angular/material/snack-bar';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-profile',
  templateUrl: './index-profile.component.html',
  styleUrls: ['./index-profile.component.css'],
  providers: [UserService],
})
export class ProfileComponent implements OnInit, AfterViewChecked {
  @ViewChild('imgPublication')
  inputImgs: ElementRef;

  constructor(
    private _sessionService: SessionService,
    private _userService: UserService,
    private _publicationService: PublicationService,
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
          this.getFollowersAndFollowed(this.id);
          this.getUserPublications(this.id);
        }, 1200);
      } else {
        this._userService.getUserById(parseInt(this.id_user)).subscribe(
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
              this._userService.isFollowingThatUser(this.user.id, this.id).subscribe(
                  (result) => {
                    if (result != null) {
                      this.id_follow = result.id;
                      this.status_follow = result.status_follow;
                    } else {
                      this.id_follow = null;
                      this.status_follow = 1;
                    }
                  }, (error) => {
                    console.log(error);
              });
              this.getFollowersAndFollowed(this.id);
              this.getUserPublications(this.id);
            }, (error) => {
              console.log(error);
        });
      }
    });
  }

  message: string;
  horizontalPosition: MatSnackBarHorizontalPosition = 'right';
  verticalPosition: MatSnackBarVerticalPosition = 'bottom';

  ngAfterViewChecked(): void {
    if (parseInt(this.id_user) != this.user.id) {
      this.setLikeStyles(this.publications);
    }
  }

  setLikeStyles(publications) {
    if (publications != null) {
      publications.forEach((publication, index) => {
        let likeIcon = document.getElementById(index + 'likeIcon');
        if(publication.isLiking == 0 && likeIcon != null) {
          likeIcon.style.color = 'rgba(156, 163, 175, var(--tw-text-opacity))';
          likeIcon.onmouseover = function () { likeIcon.style.color = '#039be5'; };
          likeIcon.onmouseout = function () { likeIcon.style.color = 'rgba(156, 163, 175, var(--tw-text-opacity))'; };
        } else if (publication.isLiking == 1 && likeIcon != null) {
          likeIcon.style.color = '#F50303';
          likeIcon.onmouseover = function () { likeIcon.style.color = 'rgba(29, 78, 216, var(--tw-text-opacity))'; };
          likeIcon.onmouseout = function () { likeIcon.style.color = 'rgba(59, 130, 246, var(--tw-text-opacity))'; };
        }
      })
    }
  }

  deletePublication(index: number) {
    this._publicationService.delete(this.user.id, this.publications[index].id).subscribe(
        (result) => {
          this.getUserPublications(this.id);
        }, (error) => {
          console.log(error);
    });
  }

  deletingAnimation(index: number) {
    Swal.fire({
      title: 'Estás seguro de que quieres eliminar esta publicación?',
      text: 'Esta acción es irreversible.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        this.deletePublication(index);
        Swal.fire({ title: 'Eliminando publicación...', showConfirmButton: false, timerProgressBar: true, timer: 1000,
          didOpen: () => { Swal.showLoading(); },
        }).then((result) => {
          if (result.dismiss === Swal.DismissReason.timer) {
            Swal.fire({ title: 'Publicación eliminada.', position: 'center', icon: 'success',  showConfirmButton: false, timer: 1000, });
          }
        });
      }
    });
  }

  updateLikeStatus(index: number) {
    let likeIcon = document.getElementById(index + 'likeIcon');
    if (this.publications[index].isLiking == 1) {
      this.publications[index].n_likes--;
      this.publications[index].isLiking = 0;
      likeIcon.style.color = '#F50303';
      likeIcon.onmouseover = function () { likeIcon.style.color = 'rgba(107, 114, 128, var(--tw-text-opacity))'; };
      likeIcon.onmouseout = function () { likeIcon.style.color = 'rgba(156, 163, 175, var(--tw-text-opacity))'; };
      this.updateStatusLike(index);
    } else {
      this.publications[index].n_likes++;
      this.publications[index].isLiking = 1;
      likeIcon.style.color = '#F50303';
      likeIcon.onmouseover = function () { likeIcon.style.color = '#039be5'; };
      likeIcon.onmouseout = function () { likeIcon.style.color = 'rgba(59, 130, 246, var(--tw-text-opacity))'; };
      this.addLike(index);
    }
  }

  addLike(index: number) {
    this._publicationService.likePublication(this.publications[index], this.user.id).subscribe();
  }

  updateStatusLike(index: number) {
    this._publicationService.updateLikeStatus(this.publications[index], this.user.id).subscribe();
  }

  requestOrFollowUser() {
    if (this.isPrivate == 0) {
      this.status_follow = 3;
      this.n_followers++;
      this.message = 'Añadido a seguidos.';
    } else {
      this.status_follow = 2;
      this.message = 'Solicitud de amistad enviada.';
    }
    this._userService.requestOrFollowUser(this.id_follow, this.user.id, this.id, this.status_follow).subscribe(
        (result) => {
          this.id_follow = result.id;
          this.openSnackBar(this.message);
        },(error) => {
          console.log(error);
    });
  }

  updateFollowRequest() {
    if (this.status_follow == 3) {
      this.n_followers--;
      this.message = 'Eliminado de seguidos.';
    } else {
      this.message = 'Solicitud de amistad cancelada.';
    }
    this.status_follow = 1;
    this._userService.updateFollowRequest(this.id_follow,  this.user.id, this.id, this.status_follow).subscribe(
        (result) => {
          this.id_follow = result.id;
          this.openSnackBar(this.message);
        },(error) => {
          console.log(error);
    });
  }

  followersList: Array<User> = [];
  followedList: Array<User> = [];
  getFollowersAndFollowed(id_user: number) {
    this._userService.getFollowers(id_user).subscribe(
      (result) => {
        if (result != null) {
          this.followersList = result;
          this.n_followers = this.followersList.length;
        } else {
          this.n_followers = 0;
        }
      }, (error) => {
        console.error(error);
    });
    this._userService.getFollowed(id_user).subscribe(
      (result) => {
        if (result != null) {
          this.followedList = result;
          this.n_followed = this.followedList.length;
        } else {
          this.n_followed = 0;
        }
      },(error) => {
        console.error(error);
    });
  }

  getUserPublications(id_user: number) {
    this._publicationService.getUserPublications(id_user).subscribe(
      (result) => {
        if (result != null) {
          result.forEach((publication) => {
            publication.upload_date = this.adaptDateOfPublication(publication.upload_date);
          });
          this.publications = result;
        } else {
          this.publications = null;
        }
      }, (error) => {
        console.log(error);
    });
  }

  openSnackBar(message: string) {
    this._snackBar.open(message, 'OK', { duration: 1000, horizontalPosition: this.horizontalPosition, verticalPosition: this.verticalPosition });
  }

  adaptDateOfPublication(upload_date: string) {
    const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
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
    let adaptedDate = nameDay + ', ' + day + ' de ' + nameMonth + ' de ' + year + ', a las ' + hourAndMin;
    return adaptedDate;
  }
}
