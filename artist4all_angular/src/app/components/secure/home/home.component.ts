import { AfterViewChecked, Component, ElementRef, OnInit } from '@angular/core';
import { User } from 'src/app/core/models/user';
import { SessionService } from 'src/app/core/services/session.service';
import { Publication } from 'src/app/core/models/publication';
import { UserService } from 'src/app/core/services/user.service';
import { Router } from '@angular/router';
import { PublicationService } from 'src/app/core/services/publication.service';
import {
  MatSnackBar,
  MatSnackBarHorizontalPosition,
  MatSnackBarVerticalPosition,
} from '@angular/material/snack-bar';
import { FormGroup, FormControl, Validators, FormBuilder} from '@angular/forms';

import { ViewChild } from '@angular/core';
@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css'],
  providers: [UserService],
})
export class HomeComponent implements OnInit, AfterViewChecked {
  @ViewChild('imgPublication')
  inputImgs: ElementRef;

  constructor(
    private _sessionService: SessionService,
    private _publicationService: PublicationService,
    private _router: Router,
    private _snackBar: MatSnackBar,

    ) {}

  user = this._sessionService.getCurrentUser();
  id = this.user.id;
  token = this._sessionService.getCurrentToken();

  publications: Array<Publication> = [];
  ngOnInit(): void {
    this._publicationService.getFollowedPublications(this.id).subscribe(
      (result) => {
        if (result != null) {
          result.forEach((publication) => {
            publication.upload_date = this.adaptDateOfPublication(publication.upload_date);
          });
          this.publications = result;
        }
      }, (error) => {
        console.log(error);
    });
  }

  ngAfterViewChecked(): void {
    this.setLikeStyles(this.publications);
  }

  setLikeStyles(publications) {
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

  images = [];
  imgToUpload: FileList = null;
  showingImgHint: boolean = false;
  addImgPublication(imgPublication: FileList) {
    this.images = [];
    if (imgPublication !== null) {
      for (var i = 0; i < imgPublication.length; i++) {
        let file = imgPublication.item(i);
        let allowed = ['image/gif', 'image/png', 'image/jpg', 'image/jpeg'];
        if (!allowed.includes(file.type)) this.showingImgHint = true;
        else this.showingImgHint = false;
      }
      if (!this.showingImgHint) {
        this.imgToUpload = imgPublication;
        if (imgPublication && imgPublication[0]) {
          var filesAmount = imgPublication.length;
          for (let i = 0; i < filesAmount; i++) {
            var reader = new FileReader();
            reader.onload = (event:any) => {
              this.images.push(event.target.result);
            }
            reader.readAsDataURL(imgPublication[i]);
          }
        }
      }
    }
  }

  removeSelectedImgs() {
    this.images = [];
    this.imgToUpload = null;
    this.inputImgs.nativeElement.value = null;
  }

  bodyPublication: string = '';
  createPublication() {
    this.message = 'Publicación creada.';
    this._publicationService.create(
      new Publication(null, this.user, this.imgToUpload, this.bodyPublication, null, 0, 0, 0, 0)).subscribe(
        (result) => {
          this.openSnackBar(this.message);
          this.removeSelectedImgs();
        },
        (error) => {
          console.log(error);
    });
  }

  message: string;
  horizontalPosition: MatSnackBarHorizontalPosition = 'right';
  verticalPosition: MatSnackBarVerticalPosition = 'bottom';
  openSnackBar(message: string) {
    this._snackBar.open(message, 'OK', { duration: 2000, horizontalPosition: this.horizontalPosition, verticalPosition: this.verticalPosition });
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
