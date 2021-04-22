import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { error } from 'protractor';
import { Publication } from 'src/app/core/models/publication';
import { User } from 'src/app/core/models/user';
import { CommentService } from 'src/app/core/services/comment.service';
import { PublicationService } from 'src/app/core/services/publication.service';
import { SessionService } from 'src/app/core/services/session.service';
import { Comment } from '../../../../core/models/comment';

@Component({
  selector: 'app-comment',
  templateUrl: './comment.component.html',
  styleUrls: ['./comment.component.css']
})
export class CommentComponent implements OnInit {

  constructor(
    private _sessionService: SessionService,
    private _commentService: CommentService,
    private _publicationService: PublicationService,
    private _formBuilder: FormBuilder,
    private _activeRoute: ActivatedRoute,
    ) { }

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

  user: User;
  token = this._sessionService.getCurrentToken();

  publication: Publication;
  comments: Array<Comment>;
  subcomments: Array<Comment>;
  commentForm: FormGroup;
  commentFormResponse: FormGroup;
  id_publication: string = "";
  ngOnInit(): void {
    this.user = this._sessionService.getCurrentUser();
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
    this._activeRoute.paramMap.subscribe((params) => {
    this.id_publication = params.get('id_publication');
      this._commentService.getPublicationComments(this.id, parseInt(this.id_publication)).subscribe(
      (result) => {
        if (result != null) {
          result.forEach((comment) => {
            comment.comment_date = this.adaptDateOfComment(comment.comment_date);
          });
          this.comments = result;
        }
      }, (error) => {
        console.log(error);
      });
    });
    this.commentForm = this._formBuilder.group({
      id: [null],
      user: [this.user],
      bodyComment: ['', []],
      // bodyComment: ['', [Validators.required, Validators.minLength(1), Validators.maxLength(255)]],
      isEdited: [0],
      comment_date: [null],
      id_publication: [parseInt(this.id_publication)],
      id_comment_reference: [null]
    });
  }




  responseFormIndexAux = -1;
  showingForm: boolean = false;
  toggleResponseForm(index: number): void {
    console.log(this.comments[index]);
    if (index != this.responseFormIndexAux) {
      this.showingForm = false;
    }
    this.responseFormIndexAux = index;
    this.subCommentFormAuxIndex = -1;
    let formSubcommentResponseForm = document.getElementById(index + 'formSubcommentResponseForm');
    let formResponseContainer = document.getElementById(index + 'formResponseContainer');
    let subcommentContainer = document.getElementById(index + 'subcommentContainer');
    if (!this.showingForm) {
      if (formResponseContainer) formResponseContainer.style.display = 'block';
      this.showingForm = true;
      if (subcommentContainer) subcommentContainer.style.display = 'none';
      this.showingSubcomments = false;
      if (formSubcommentResponseForm) formSubcommentResponseForm.style.display = 'none';
      this.showingSubcommentForm = false;
      this.createResponseForm(index);
    } else {
      formResponseContainer.style.display = 'none';
      this.showingForm = false;
    }
  }


  subcommentIndexAux = -1;
  showingSubcomments: boolean = false;
  //TODO: poner un spinner de carga antes de mostrar o sacar algun div
  toggleSubcomments(index: number) {
    this.subcomments = [];
    if (index != this.subcommentIndexAux) {
      this.showingSubcomments = false;
    }
    this.subCommentFormAuxIndex = -1;
    this.subcommentIndexAux = index;
    this._commentService.getCommentSubcomments(this.user.id, this.comments[index].id_publication, this.comments[index].id).subscribe(
      (result) => {
        if (result != null) {
          result.forEach((subcomment) => {
            subcomment.comment_date = this.adaptDateOfComment(subcomment.comment_date);
          });
          this.subcomments = result;
        }
        let formResponseContainer = document.getElementById(index + 'formResponseContainer');
        let subcommentContainer = document.getElementById(index + 'subcommentContainer');
        if (!this.showingSubcomments) {
          if (this.subcomments.length == 0) {
            if (subcommentContainer) {
              subcommentContainer.innerHTML = 'Este comentario actualmente no tiene respuestas.';
            }
          }
          if (subcommentContainer) subcommentContainer.style.display = 'block';
          this.showingSubcomments = true;
          if (formResponseContainer) formResponseContainer.style.display = 'none';
          this.showingForm = false;
        } else {
          if (subcommentContainer) subcommentContainer.style.display = 'none';
          this.showingSubcomments = false;
        }
      }, (error) => {
        console.log(error);
    });
  }

  subCommentFormAuxIndex = -1;
  showingSubcommentForm: boolean = false;
  toggleSubcommentResponseForm(index: number): void {
    if (index != this.subCommentFormAuxIndex) {
      this.showingSubcommentForm = false;
    }
    this.subCommentFormAuxIndex = index;
    this.responseFormIndexAux = -1;
    let formSubcommentResponseForm = document.getElementById(index + 'formSubcommentResponseForm');
    let formResponseContainer = document.getElementById(index + 'formResponseContainer');
    if (!this.showingSubcommentForm) {
      if (formSubcommentResponseForm) formSubcommentResponseForm.style.display = 'block';
      this.showingSubcommentForm = true;
      if (formResponseContainer) formResponseContainer.style.display = 'none';
      this.showingForm = false;
      this.createResponseForm(index);
    } else {
      if (formSubcommentResponseForm) formSubcommentResponseForm.style.display = 'none';
      this.showingSubcommentForm = false;
    }
  }

  createResponseForm(index: number) {
    this.commentFormResponse = this._formBuilder.group({
      id: [null],
      user: [this.user],
      bodyComment: ['', []],
      // bodyComment: ['', [Validators.required, Validators.minLength(1), Validators.maxLength(255)]],
      isEdited: [0],
      comment_date: [null],
      id_publication: [parseInt(this.id_publication)],
      id_comment_reference: [this.comments[index].id]
    });
  }

  isValidFormSubmitted = null;
  //TODO: Contador de carácteres


  get bodyComment() { return this.commentForm.get('bodyComment'); }
  get bodyResponse() { return this.commentFormResponse.get('bodyComment'); }

  newComment: Comment;
  postComment() {
    this.isValidFormSubmitted = false;
    if (this.commentForm.invalid) {
      return;
    }
    this.isValidFormSubmitted = true;
    let comment: Comment = this.commentForm.value;
    this._commentService.postComment(comment).subscribe(
      (result) => {
        this.newComment = result;
        this.comments.unshift(this.newComment);
        this.commentForm.reset();
        this.commentForm.controls['user'].setValue(this.user);
        this.commentForm.controls['id_publication'].setValue(parseInt(this.id_publication));
        this.commentForm.controls['isEdited'].setValue(0);
        this.commentForm.controls['id_comment_reference'].setValue(null);
      }, (error) => {
        console.log(error);
    });
  }

  newSubcomment: Comment;
  postResponse(index: number) {
    console.log(this.commentFormResponse.controls.bodyResponse)
    this.isValidFormSubmitted = false;
    if (this.commentFormResponse.invalid) {
      return;
    }
    this.isValidFormSubmitted = true;
    let subcomment: Comment = this.commentFormResponse.value;
    this._commentService.postComment(subcomment).subscribe(
      (result) => {
        console.log(result);
        this.newSubcomment = result;
        this.subcomments.unshift(this.newSubcomment);
        this.commentFormResponse.reset();
        this.commentFormResponse.controls['user'].setValue(this.user);
        this.commentFormResponse.controls['id_publication'].setValue(parseInt(this.id_publication));
        this.commentFormResponse.controls['isEdited'].setValue(0);
        this.commentFormResponse.controls['id_comment_reference'].setValue(this.comments[index].id);
      }, (error) => {
        console.log(error);
    });
  }

  adaptDateOfComment(upload_date: string) {
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
