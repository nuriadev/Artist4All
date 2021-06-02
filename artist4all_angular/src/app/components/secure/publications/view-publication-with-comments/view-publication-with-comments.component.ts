import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { Publication } from 'src/app/core/models/publication';
import { User } from 'src/app/core/models/user';
import { CommentService } from 'src/app/core/services/comment.service';
import { PublicationService } from 'src/app/core/services/publication.service';
import { SessionService } from 'src/app/core/services/session.service';
import { Comment } from '../../../../core/models/comment';
import { MatSnackBar, MatSnackBarHorizontalPosition, MatSnackBarVerticalPosition } from '@angular/material/snack-bar';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-view-publication-with-comments',
  templateUrl: './view-publication-with-comments.component.html',
  styleUrls: ['./view-publication-with-comments.component.css']
})
export class ViewPublicationWithCommentsComponent implements OnInit {

  constructor(
    private _sessionService: SessionService,
    private _commentService: CommentService,
    private _publicationService: PublicationService,
    private _formBuilder: FormBuilder,
    private _activeRoute: ActivatedRoute,
    private _snackBar: MatSnackBar,
    ) { }

  id: number;
  imgAvatar: FileList;
  user: User;
  token = this._sessionService.getCurrentToken();
  publication: Publication;

  comments: Array<Comment> = [];
  subcomments: Array<Comment> = [];
  commentForm: FormGroup;
  commentFormResponse: FormGroup;
  editCommentForm: FormGroup;
  id_publication: string = "";
  ngOnInit(): void {
    this.user = this._sessionService.getCurrentUser();
    this.id = this.user.id;
    this.imgAvatar = this.user.imgAvatar;
    this._activeRoute.paramMap.subscribe((params) => {
    this.id_publication = params.get('id_publication');
    this._publicationService.getPublicationById(this.user.id, parseInt(this.id_publication)).subscribe(
      (result) => {
        this.publication = result;
      }, (error) => {
        console.log(error);
    })
      this.getPublicationComments();
    });
    this.commentForm = this._formBuilder.group({
      id: [null],
      user: [this.user],
      bodyComment: ['', [Validators.required, Validators.maxLength(255)]],
      isEdited: [0],
      comment_date: [null],
      id_publication: [parseInt(this.id_publication)],
      id_comment_reference: [null],
      user_reference: [null]
    });
  }

  contador: number = 255;
  showingBodyHint: boolean = false;
  countCharacters(event) {
    this.contador = 255;
    this.contador -= event.target.value.length;
    if (event.target.value.length > 255) this.showingBodyHint = true;
    else this.showingBodyHint = false;
  }

  contadorSubcommentResponse: number = 255;
  showingBodySubcommentResponse: boolean = false;
  countCharactersSubcommentResponse(event) {
    this.contadorSubcommentResponse = 255;
    this.contadorSubcommentResponse -= event.target.value.length;
    if (event.target.value.length > 255) this.showingBodySubcommentResponse = true;
    else this.showingBodySubcommentResponse = false;
  }

  contadorResponse: number = 255;
  showingBodyResponseHint: boolean = false;
  countCharactersResponse(event) {
    this.contadorResponse = 255;
    this.contadorResponse -= event.target.value.length;
    if (event.target.value.length > 255) this.showingBodyResponseHint = true;
    else this.showingBodyResponseHint = false;
  }

  contadorEdit: number = 255;
  showingBodyEditHint: boolean = false;
  showingBodyEditRequired = false;
  countCharactersEdit(event) {
    this.contadorEdit = 255;
    this.contadorEdit -= event.target.value.length;
    if (event.target.value.length > 255) this.showingBodyEditHint = true;
    else this.showingBodyEditHint = false;
    if (event.target.value.length == 0) this.showingBodyEditRequired = true;
    else this.showingBodyEditRequired = false;
  }

  contadorEditSubcomments: number = 255;
  showingBodyEditSubcommentsHint: boolean = false;
  showingBodyEditSubcommentsRequired = false;
  countCharactersEditSubcomments(event) {
    this.contadorEditSubcomments = 255;
    this.contadorEditSubcomments -= event.target.value.length;
    if (event.target.value.length > 255) this.showingBodyEditSubcommentsHint = true;
    else this.showingBodyEditSubcommentsHint = false;
    if (event.target.value.length == 0) this.showingBodyEditSubcommentsRequired = true;
    else this.showingBodyEditSubcommentsRequired = false;
  }

  getPublicationComments() {
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
  }

  responseFormIndexAux = -1;
  showingForm: boolean = false;
  toggleResponseForm(index: number): void {
    if (this.commentFormResponse) this.showingBodyResponseHint = false;
    if (index != this.responseFormIndexAux) {
      this.showingForm = false;
    }
    this.responseFormIndexAux = index;
    this.subcommentFormAuxIndex = -1;
    this.subcommentIndexAux = -1;
    this.contadorResponse = 255;
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
      this.commentFormResponse = this._formBuilder.group({
        id: [null],
        user: [this.user],
        bodyComment: ['', [Validators.required, Validators.maxLength(255)]],
        isEdited: [0],
        comment_date: [null],
        id_publication: [parseInt(this.id_publication)],
        id_comment_reference: [this.comments[index].id],
        user_reference: [this.comments[index].user]
      });
    } else {
      formResponseContainer.style.display = 'none';
      this.showingForm = false;
    }
  }

  subcommentIndexAux = -1;
  showingSubcomments: boolean = false;
  toggleSubcomments(index: number) {
    this.subcomments = [];
    if (index != this.subcommentIndexAux) {
      this.showingSubcomments = false;
    }
    this.responseFormIndexAux = -1;
    this.subcommentFormAuxIndex = -1;
    this.subcommentIndexAux = index;
    let formResponseContainer = document.getElementById(index + 'formResponseContainer');
    let subcommentContainer = document.getElementById(index + 'subcommentContainer');
    this._commentService.getCommentSubcomments(this.user.id, this.comments[index].id_publication, this.comments[index].id).subscribe(
      (result) => {
        if (result != null) {
          result.forEach((subcomment) => {
            subcomment.comment_date = this.adaptDateOfComment(subcomment.comment_date);
          });
          this.subcomments = result;
        } else {
          this.subcomments = null;
        }
      }, (error) => {
        console.log(error);
    });
    this.subcomments = [];
    if (!this.showingSubcomments) {
      if (subcommentContainer) subcommentContainer.style.display = 'block';
      this.showingSubcomments = true;
      if (formResponseContainer) formResponseContainer.style.display = 'none';
      this.showingForm = false;
    } else {
      if (subcommentContainer) subcommentContainer.style.display = 'none';
      this.showingSubcomments = false;
    }
  }

  subcommentFormAuxIndex = -1;
  showingSubcommentForm: boolean = false;
  toggleSubcommentResponseForm(indexSubcomments: number, indexComments: number): void {
    if (this.commentFormResponse) this.showingBodySubcommentResponse = false;
    if (indexSubcomments != this.subcommentFormAuxIndex) {
      this.showingSubcommentForm = false;
    }
    this.responseFormIndexAux = -1;
    this.contadorSubcommentResponse = 255;
    this.subcommentFormAuxIndex = indexSubcomments;
    let formSubcommentResponseForm = document.getElementById(indexSubcomments + 'formSubcommentResponseForm');
    let formResponseContainer = document.getElementById(indexSubcomments + 'formResponseContainer');
    if (!this.showingSubcommentForm) {
      if (formSubcommentResponseForm) formSubcommentResponseForm.style.display = 'block';
      this.showingSubcommentForm = true;
      if (formResponseContainer) formResponseContainer.style.display = 'none';
      this.showingForm = false;
      this.commentFormResponse = this._formBuilder.group({
        id: [null],
        user: [this.user],
        bodyComment: ['', [Validators.required, Validators.maxLength(255)]],
        isEdited: [0],
        comment_date: [null],
        id_publication: [parseInt(this.id_publication)],
        id_comment_reference: [this.comments[indexComments].id],
        user_reference: [this.subcomments[indexSubcomments].user]
      });
    } else {
      if (formSubcommentResponseForm) formSubcommentResponseForm.style.display = 'none';
      this.showingSubcommentForm = false;
    }
  }

  inComments: boolean = true;
  inSubcomments: boolean = false;
  editCommentIndexAux = -1;
  showingEditForm = false;
  toggleEditForm(index: number, commentsArray: Array<Comment>, type: number) {
    if (this.editCommentForm) {
      this.showingBodyEditHint = false;
      this.showingBodyEditRequired = false;
      this.showingBodyEditSubcommentsHint = false;
      this.showingBodyEditSubcommentsRequired = false;
    }
    if (index != this.editCommentIndexAux) {
      this.showingEditForm = false;
    }
    if (type == 0) {
      this.inComments = true;
      this.inSubcomments = false;
    } else if (type == 1) {
      this.inComments = false;
      this.inSubcomments = true;
    }
    this.contadorEditSubcomments = 255;
    this.contadorEditSubcomments -= commentsArray[index].bodyComment.length;
    this.contadorEdit = 255;
    this.contadorEdit -= commentsArray[index].bodyComment.length;
    this.editCommentIndexAux = index;
    this.editCommentForm = this._formBuilder.group({
      id: [commentsArray[index].id],
      user: [this.user],
      bodyComment:[commentsArray[index].bodyComment, [Validators.required, Validators.maxLength(255)]],
      isEdited: [1],
      comment_date: [null],
      id_publication: [parseInt(this.id_publication)],
      id_comment_reference: [commentsArray[index].id],
      user_reference: [commentsArray[index].user]
    });
    if (!this.showingEditForm) this.showingEditForm = true;
    else this.showingEditForm = false;
  }

  isValidFormSubmitted = null;

  get bodyComment() { return this.commentForm.get('bodyComment'); }
  get bodyResponse() { return this.commentFormResponse.get('bodyComment'); }

  newComment: Comment;
  postComment() {
    this.subcommentIndexAux = -1;
    this.responseFormIndexAux = -1;
    this.subcommentFormAuxIndex = -1;
    this.showingEditForm = false;
    this.editCommentIndexAux = -1;
    this.isValidFormSubmitted = false;
    if (this.commentForm.invalid) {
      return;
    }
    this.isValidFormSubmitted = true;
    this.message = 'Comentario publicado.'
    let comment: Comment = this.commentForm.value;
    this._commentService.postComment(comment).subscribe(
      (result) => {
        this.contador = 255;
        this.openSnackBar(this.message);
        result.comment_date = this.adaptDateOfComment(result.comment_date);
        this.newComment = result;
        this.comments.unshift(this.newComment);
        this.commentForm.reset();
        this.commentForm.controls['user'].setValue(this.user);
        this.commentForm.controls['id_publication'].setValue(parseInt(this.id_publication));
        this.commentForm.controls['isEdited'].setValue(0);
        this.commentForm.controls['id_comment_reference'].setValue(null);
        this.commentForm.controls['user_reference'].setValue(null);
      }, (error) => {
        console.log(error);
    });
  }

  newSubcomment: Comment;
  postResponse(indexComments: number, indexSubcomments: number, type: number) {
    if (this.subcomments == null) this.subcomments = [];
    this.isValidFormSubmitted = false;
    if (this.commentFormResponse.invalid) {
      return;
    }
    this.isValidFormSubmitted = true;
    this.message = 'Respuesta publicada.';
    let subcomment: Comment = this.commentFormResponse.value;
    this._commentService.postComment(subcomment).subscribe(
      (result) => {
        this.contadorResponse = 255;
        this.openSnackBar(this.message);
        result.comment_date = this.adaptDateOfComment(result.comment_date);
        this.newSubcomment = result;
        this.subcomments.unshift(this.newSubcomment);
        this.commentFormResponse.reset();
        this.commentFormResponse.controls['user'].setValue(this.user);
        this.commentFormResponse.controls['id_publication'].setValue(parseInt(this.id_publication));
        this.commentFormResponse.controls['isEdited'].setValue(0);
        this.commentFormResponse.controls['id_comment_reference'].setValue(this.comments[indexComments].id);
        if (type == 1) {
          this.commentFormResponse.controls['user_reference'].setValue(this.subcomments[indexSubcomments].user);
          this.subcommentFormAuxIndex = -1;
        } else {
          this.commentFormResponse.controls['id_comment_reference'].setValue(this.comments[indexComments].user);
          this.responseFormIndexAux = -1;
        }
      }, (error) => {
        console.log(error);
    });
  }

  editedComment: Comment;
  editComment(index: number, commentArray: Array<Comment>, type: number) {
    if (this.showingEditForm) {
      this.isValidFormSubmitted = false;
      if (this.editCommentForm.invalid) {
        return;
      }
      this.isValidFormSubmitted = true;
      commentArray[index].bodyComment = this.editCommentForm.get['bodyComment'];
      commentArray[index].isEdited = this.editCommentForm.get['isEdited'];
      this.message = 'Comentario editado.';
      let comment: Comment = this.editCommentForm.value;
      this._commentService.editComment(comment).subscribe(
        (result) => {
          this.openSnackBar(this.message);
          result.comment_date = this.adaptDateOfComment(result.comment_date);
          this.editedComment = result;
          commentArray[index] = this.editedComment;
          this.showingEditForm = false;
          this.editCommentForm.reset();
          this.editCommentForm.controls['isEdited'].setValue(1);
          if (type == 1) this.subcommentFormAuxIndex = -1;
          else this.responseFormIndexAux = -1;
        }, (error) => {
          console.log(error);
      });
    }
  }

  deleteComment(index: number, commentsArray:Array<Comment>) {
    this._commentService.delete(this.user.id, parseInt(this.id_publication), commentsArray[index]).subscribe(
      (result) => {
        commentsArray.splice(index, 1);
      }, (error) => {
        console.log(error);
    });
  }

  deleteAnimation(index: number, commentsArray:Array<Comment>) {
    Swal.fire({
      title: '¿Estás seguro de que quieres eliminar este comentario?',
      text: 'Esta acción es irreversible.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Borrar comentario',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        this.editCommentIndexAux = -1;
        this.deleteComment(index, commentsArray);
        Swal.fire({ title: 'Borrando comentario...', showConfirmButton: false, timerProgressBar: true, timer: 1000,
          didOpen: () => { Swal.showLoading(); },
        }).then((result) => {
          if (result.dismiss === Swal.DismissReason.timer) {
            Swal.fire({ title: 'Comentario eliminado', position: 'center', icon: 'success',  showConfirmButton: false, timer: 1000, });
          }
        });
      }
    });
  }

  message: string;
  horizontalPosition: MatSnackBarHorizontalPosition = 'right';
  verticalPosition: MatSnackBarVerticalPosition = 'bottom';
  openSnackBar(message: string) {
    this._snackBar.open(message, 'OK', { duration: 2000, horizontalPosition: this.horizontalPosition, verticalPosition: this.verticalPosition });
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
